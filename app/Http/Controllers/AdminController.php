<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\UnitKerja;

// Model baru
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

// Pastikan package barryvdh/laravel-dompdf sudah terinstall

class AdminController extends Controller
{
    public function index()
    {
        // Ambil data urut dari yang terbaru
        $pengajuan = PengajuanCuti::with(['pegawai.unit_kerja'])->latest()->get();
        return view('admin.dashboard', compact('pengajuan'));
    }

    // --- 1. FITUR IMPORT (Dengan Logic Unit Kerja Otomatis) ---
    public function importPegawai(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file_csv');

        // Fix untuk Mac/Linux/Windows line endings
        ini_set('auto_detect_line_endings', true);

        // 1. DETEKSI DELIMITER (Koma atau Titik Koma?)
        $delimiter = ','; // Default
        $preview = fopen($file->getRealPath(), 'r');
        $firstLine = fgets($preview);
        if ($firstLine && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        }
        fclose($preview);

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Lewati Header
            fgetcsv($handle, 1000, $delimiter);

            DB::beginTransaction();
            $successCount = 0;
            $skippedCount = 0;

            try {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                    // Validasi Dasar: Baris harus punya minimal 3 kolom
                    if (!$row || count($row) < 3) {
                        $skippedCount++;
                        continue;
                    }

                    // Mapping Kolom (Sesuaikan Index CSV)
                    // Index 2: NIP
                    $nip = preg_replace('/[^0-9]/', '', $row[2] ?? '');

                    // Skip jika NIP kosong/tidak valid
                    if (!$nip) {
                        $skippedCount++;
                        continue;
                    }

                    // Bersihkan Nama (UTF-8 Safe)
                    $namaLengkap = mb_convert_encoding($row[1] ?? 'Tanpa Nama', 'UTF-8', 'UTF-8');

                    // --- LOGIC UNIT KERJA ---
                    $namaUnitCSV = $row[5] ?? 'Umum';

                    $unitKerja = UnitKerja::firstOrCreate(
                        ['nama_unit' => $namaUnitCSV],
                        [
                            'nama_kepala' => $row[16] ?? null, // Index 16: Nama Atasan
                            'nip_kepala' => $row[17] ?? null,
                            'jabatan_kepala' => $row[18] ?? 'Kepala Unit'
                        ]
                    );

                    // --- SIMPAN PEGAWAI ---
                    Pegawai::updateOrCreate(
                        ['nip' => $nip],
                        [
                            'nama_lengkap' => $namaLengkap,
                            'unit_kerja_id' => $unitKerja->id,
                            'masa_kerja' => $row[3] ?? null,
                            'jabatan' => $row[4] ?? null,

                            // Sisa Cuti (Safe Check)
                            'sisa_cuti_n_2' => is_numeric($row[11] ?? null) ? $row[11] : 0,
                            'sisa_cuti_n_1' => is_numeric($row[12] ?? null) ? $row[12] : 0,
                            'sisa_cuti_n' => is_numeric($row[13] ?? null) ? $row[13] : 12,

                            'alamat' => $row[14] ?? null,
                            'telepon' => $row[15] ?? null,
                        ]
                    );

                    $successCount++;
                }

                DB::commit();

                // Feedback Lebih Jelas
                if ($successCount == 0) {
                    return back()->with('error', "Import Gagal. Tidak ada data yang terbaca. Pastikan file adalah CSV (Comma Delimited). Terdeteksi Delimiter: '$delimiter'");
                }

                return back()->with('success', "Import Selesai! $successCount data masuk/update. $skippedCount baris dilewati.");

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error Sistem: ' . $e->getMessage());
            }
            fclose($handle);
        }
        return back()->with('error', 'File tidak dapat dibuka.');
    }

    // --- 2. FITUR UPDATE STATUS (Pending -> Disetujui/Ditolak) ---
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = PengajuanCuti::with('pegawai.unit_kerja')->findOrFail($id);

        // Ambil data pejabat penilai dari Unit Kerja pegawai tersebut
        $pejabat = $pengajuan->pegawai->unit_kerja;

        $pengajuan->update([
            'status' => $request->status, // 'disetujui' atau 'ditolak'

            // Simpan Snapshot Pejabat saat tombol diklik (Penting untuk sejarah)
            'nama_pejabat' => $pejabat->nama_kepala ?? '-',
            'nip_pejabat' => $pejabat->nip_kepala ?? '-',
            'jabatan_pejabat' => $pejabat->jabatan_kepala ?? '-',

            'keputusan_pejabat' => strtoupper($request->status), // DISETUJUI / DITOLAK
            'catatan_pejabat' => $request->catatan // Alasan jika ditolak
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    // --- 3. CETAK PDF (Opsional, jika sudah install dompdf) ---
    public function cetakPDF($id)
    {
        // 1. Ambil data pengajuan beserta relasi pegawai & unit kerja
        $pengajuan = PengajuanCuti::with(['pegawai.unit_kerja'])->findOrFail($id);

        // 2. Ambil Data Atasan Langsung (Kepala Unit Kerja Pegawai tsb)
        $atasanLangsung = $pengajuan->pegawai->unit_kerja;

        // 3. Ambil Data Pejabat Berwenang (Kepala Kemenag/Instansi dari Setting)
        $kepalaInstansi = \App\Models\InstansiSetting::first();

        $data = [
            'cuti' => $pengajuan,
            'pegawai' => $pengajuan->pegawai,
            // Data Atasan Langsung (Kolom VII)
            'atasan' => [
                'nama' => $atasanLangsung->nama_kepala ?? '....................',
                'nip'  => $atasanLangsung->nip_kepala ?? '....................',
                'jabatan' => $atasanLangsung->jabatan_kepala ?? 'Kepala Unit',
            ],
            // Data Pejabat Berwenang (Kolom VIII)
            'pejabat' => [
                'nama' => $kepalaInstansi->nama_kepala ?? '....................',
                'nip'  => $kepalaInstansi->nip_kepala ?? '....................',
                'jabatan' => $kepalaInstansi->jabatan_kepala ?? 'Kepala Instansi',
            ],
            'instansi' => $kepalaInstansi
        ];

        // 4. Load View PDF
        $pdf = Pdf::loadView('pdf.formulir_cuti', $data);

        // 5. Setup Kertas F4/Legal (Standar Persuratan)
        $pdf->setPaper('legal', 'portrait');

        // 6. Tampilkan di Browser (Stream)
        return $pdf->stream('Formulir_Cuti_'.$pengajuan->pegawai->nip.'.pdf');
    }

    public function indexUnitKerja()
    {
        $units = UnitKerja::all();
        return view('admin.unit_kerja', compact('units'));
    }

    public function storeUnitKerja(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:255',
            // Field pejabat opsional, boleh diisi nanti
            'nama_kepala' => 'nullable|string',
            'nip_kepala' => 'nullable|string',
            'jabatan_kepala' => 'nullable|string',
        ]);

        UnitKerja::create([
            'nama_unit' => $request->nama_unit,
            'nama_kepala' => $request->nama_kepala,
            'nip_kepala' => $request->nip_kepala,
            'jabatan_kepala' => $request->jabatan_kepala,
        ]);

        return back()->with('success', 'Unit Kerja berhasil ditambahkan!');
    }

    public function destroyUnitKerja($id)
    {
        $unit = UnitKerja::findOrFail($id);
        $unit->delete();

        return back()->with('success', 'Unit Kerja berhasil dihapus! Pegawai di unit ini sekarang berstatus tanpa unit.');
    }

    public function updateUnitKerja(Request $request, $id)
    {
        $unit = UnitKerja::findOrFail($id);
        $unit->update([
            'nama_kepala' => $request->nama_kepala,
            'nip_kepala' => $request->nip_kepala,
            'jabatan_kepala' => $request->jabatan_kepala,
        ]);

        return back()->with('success', 'Data Pejabat Unit Kerja berhasil diperbarui');
    }

    // --- MANAJEMEN PEGAWAI (SIMPLE LIST) ---
    public function indexPegawai(Request $request)
    {
        // Fitur Pencarian Sederhana
        $query = Pegawai::with('unit_kerja');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%");
        }

        $pegawais = $query->paginate(20);
        return view('admin.pegawai', compact('pegawais'));
    }

    public function storePegawai(Request $request)
    {
        // Validasi
        $request->validate([
            'nip' => 'required|unique:pegawais,nip',
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            'unit_kerja_id' => 'nullable',
            // Tambahkan validasi numeric
            'sisa_cuti_n' => 'required|numeric',
            'sisa_cuti_n_1' => 'nullable|numeric',
            'sisa_cuti_n_2' => 'nullable|numeric',
        ]);

        Pegawai::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'unit_kerja_id' => $request->unit_kerja_id,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,

            // SIMPAN DATA CUTI
            'sisa_cuti_n' => $request->sisa_cuti_n,
            'sisa_cuti_n_1' => $request->sisa_cuti_n_1 ?? 0,
            'sisa_cuti_n_2' => $request->sisa_cuti_n_2 ?? 0,
        ]);

        return redirect()->back()->with('success', 'Data Pegawai berhasil ditambahkan');
    }

    public function updatePegawai(Request $request, $id)
    {
        $pegawai = Pegawai::where('nip', $id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            // Tambahkan validasi numeric
            'sisa_cuti_n' => 'required|numeric',
            'sisa_cuti_n_1' => 'nullable|numeric',
            'sisa_cuti_n_2' => 'nullable|numeric',
        ]);

        $pegawai->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'unit_kerja_id' => $request->unit_kerja_id,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,

            // UPDATE DATA CUTI
            'sisa_cuti_n' => $request->sisa_cuti_n,
            'sisa_cuti_n_1' => $request->sisa_cuti_n_1 ?? 0,
            'sisa_cuti_n_2' => $request->sisa_cuti_n_2 ?? 0,
        ]);

        return redirect()->back()->with('success', 'Data Pegawai berhasil diperbarui');
    }

    public function destroyPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return back()->with('success', 'Pegawai dihapus');
    }

    public function indexSettings()
    {
        // Ambil data pertama, jika tidak ada buat baru (kosong)
        $setting = \App\Models\InstansiSetting::firstOrCreate([], [
            'nama_instansi' => 'Kementerian Agama Kota Gorontalo',
            'jabatan_kepala' => 'Kepala Kantor'
        ]);

        return view('admin.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $setting = \App\Models\InstansiSetting::first();

        $setting->update([
            'nama_instansi' => $request->nama_instansi,
            'nama_kepala' => $request->nama_kepala,
            'nip_kepala' => $request->nip_kepala,
            'jabatan_kepala' => $request->jabatan_kepala,
        ]);

        return back()->with('success', 'Data Instansi berhasil diperbarui!');
    }
}
