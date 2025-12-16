<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Storage;

class PublicCutiController extends Controller
{
    public function landing()
    {
        return view('welcome'); // Kita akan buat view ini dari index.html
    }

    public function index()
    {
        return view('form_cuti');
    }

    // API untuk Auto-fill Data saat NIP diketik
    public function checkNip($nip)
    {
        $pegawai = Pegawai::with('unit_kerja')->where('nip', $nip)->first();

        if ($pegawai) {
            // --- LOGIC QUOTA (REVISI) ---
            // Sisa N (Tahun Ini)
            $sisaN = $pegawai->sisa_cuti_n;

            // Sisa N-1 (Tahun Lalu) - Dibagi 2 (Hangus setengah)
            // Asumsi: sisa_cuti_n_1 di DB masih utuh, kita hitung efektifnya di sini
            $sisaN1 = floor($pegawai->sisa_cuti_n_1 / 2);

            // Sisa N-2 (Hangus total biasanya, tapi jika mau dimasukkan sesuai request)
            $sisaN2 = 0; // Biasanya N-2 hangus jika sudah masuk N

            // Total Quota Efektif (Hanya untuk Cuti Tahunan)
            $totalQuota = $sisaN + $sisaN1;

            // Tambahkan data quota ke response
            $pegawai->quota_tahunan_efektif = $totalQuota;
            $pegawai->detail_quota = "N ($sisaN) + N-1 ($sisaN1)";

            return response()->json([
                'status' => 'success',
                'data' => $pegawai
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'NIP tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nip' => 'required|exists:pegawais,nip',
            'mulai_tanggal' => 'required|date',
            'sampai_tanggal' => 'required|date', // Tambahkan validasi ini
            'lama_cuti' => 'required|numeric',
            'alasan_cuti' => 'required',
            'jenis_cuti' => 'required',
            'ttd_image' => 'required',
            'foto_selfie' => 'required',
            'file_bukti' => 'nullable|mimes:pdf|max:2048',
        ]);

        // --- REVISI: CEK APAKAH SUDAH ADA PENGAJUAN AKTIF ---
        $existingCuti = PengajuanCuti::where('pegawai_nip', $request->nip)
            ->whereIn('status', ['pending', 'disetujui']) // Yang belum selesai/ditolak
            ->exists();

        if ($existingCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda masih memiliki pengajuan cuti yang berstatus Pending atau Disetujui. Selesaikan dulu sebelum mengajukan lagi.'
            ], 422);
        }

        // --- REVISI: HITUNG DURASI TANPA SABTU MINGGU (Backend Validation) ---
        $start = Carbon::parse($request->mulai_tanggal);
        $end = Carbon::parse($request->sampai_tanggal);
        $days = 0;

        // Loop per hari untuk cek weekend
        while ($start->lte($end)) {
            if (!$start->isWeekend()) { // Jika bukan Sabtu/Minggu
                $days++;
            }
            $start->addDay();
        }

        // Validasi Cuti Tahunan vs Quota
        if ($request->jenis_cuti == 'Cuti Tahunan') {
            $pegawai = Pegawai::where('nip', $request->nip)->first();
            // Hitung ulang quota di backend biar aman
            $totalQuota = $pegawai->sisa_cuti_n + floor($pegawai->sisa_cuti_n_1 / 2);

            if ($days > $totalQuota) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Kuota Cuti Tahunan tidak mencukupi. Sisa: $totalQuota hari, Pengajuan: $days hari kerja."
                ], 422);
            }
        }

        // 2. Handle Upload Files
        $buktiPath = null;
        if ($request->hasFile('file_bukti')) {
            $buktiPath = $request->file('file_bukti')->store('bukti_cuti', 'public');
        }

        $ttdPath = $this->saveBase64($request->ttd_image, 'ttd');
        $fotoPath = $this->saveBase64($request->foto_selfie, 'selfie');

        // Ambil data atasan snapshot
        $pegawai = Pegawai::with('unit_kerja')->where('nip', $request->nip)->first();

        // 3. Simpan Database
        PengajuanCuti::create([
            'pegawai_nip' => $request->nip,
            'tanggal_pengajuan' => now(),
            'jenis_cuti' => $request->jenis_cuti,
            'alasan_cuti' => $request->alasan_cuti,
            'lama_cuti' => $days, // Gunakan hasil hitungan server (exclude weekend)
            'mulai_tanggal' => $request->mulai_tanggal,
            'sampai_tanggal' => $request->sampai_tanggal,
            'alamat_selama_cuti' => $request->alamat_selama_cuti,
            'telepon_selama_cuti' => $request->telepon_selama_cuti,
            'ttd_path' => $ttdPath,
            'foto_path' => $fotoPath,
            'file_bukti_path' => $buktiPath,
            // Snapshot Atasan
            'nama_atasan_langsung' => $pegawai->unit_kerja->nama_kepala ?? null,
            'nip_atasan_langsung' => $pegawai->unit_kerja->nip_kepala ?? null,
            'jabatan_atasan_langsung' => $pegawai->unit_kerja->jabatan_kepala ?? null,
            'status' => 'pending'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Pengajuan berhasil dikirim!']);
    }

    // Helper simpan base64 ke storage
    private function saveBase64($base64_string, $folder)
    {
        $image_parts = explode(";base64,", $base64_string);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $path = $folder . '/' . $fileName;
        Storage::disk('public')->put($path, $image_base64);
        return $path;
    }
}
