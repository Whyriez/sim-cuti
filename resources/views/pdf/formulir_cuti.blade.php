<!DOCTYPE html>
<html>
<head>
    <title>Formulir Cuti</title>
    <style>
        /* Setup Dasar */
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.2; }

        /* Table Utama */
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th, td { padding: 3px; vertical-align: top; }

        /* Table Data (Isian) */
        .table-data { border: 1px solid black; }
        .table-data td, .table-data th { border: 1px solid black; }

        /* Utilities */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-underline { text-decoration: underline; }

        /* Header Khusus */
        .header-text { font-size: 10pt; }
    </style>
</head>
<body>

<table style="border: none;">
    <tr>
        <td width="55%"></td> <td class="header-text">
            ANAK LAMPIRAN 1.b<br>
            PERATURAN BADAN KEPEGAWAIAN NEGARA<br>
            REPUBLIK INDONESIA<br>
            NOMOR 24 TAHUN 2017<br>
            TENTANG<br>
            TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <br> Gorontalo, {{ \Carbon\Carbon::parse($cuti->tanggal_pengajuan)->isoFormat('D MMMM Y') }}<br>
            <br>
            Kepada<br>
            Yth. {{ $pejabat['jabatan'] }}<br>
            {{ $instansi->nama_instansi }}<br>
            di<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat
        </td>
    </tr>
</table>

<div class="text-center text-bold" style="margin-top: 20px; margin-bottom: 10px; font-size: 12pt;">
    FORMULIR PERMINTAAN DAN PEMBERIAN CUTI
</div>

<table class="table-data">
    <tr>
        <td colspan="4" class="text-bold">I. DATA PEGAWAI</td>
    </tr>
    <tr>
        <td width="15%">Nama</td>
        <td width="35%">{{ $pegawai->nama_lengkap }}</td>
        <td width="15%">NIP</td>
        <td width="35%">{{ $pegawai->nip }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>{{ $pegawai->jabatan }}</td>
        <td>Masa Kerja</td>
        <td>{{ $pegawai->masa_kerja }}</td>
    </tr>
    <tr>
        <td>Unit Kerja</td>
        <td colspan="3">{{ $pegawai->unit_kerja->nama_unit ?? '-' }}</td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="4" class="text-bold">II. JENIS CUTI YANG DIAMBIL</td>
    </tr>
    <tr>
        <td width="40%">1. Cuti Tahunan</td>
        <td width="10%" class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti Tahunan') <span class="text-bold">V</span> @endif--}}
        </td>
        <td width="40%">2. Cuti Besar</td>
        <td width="10%" class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti Besar') <span class="text-bold">V</span> @endif--}}
        </td>
    </tr>
    <tr>
        <td>3. Cuti Sakit</td>
        <td class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti Sakit') <span class="text-bold">V</span> @endif--}}
        </td>
        <td>4. Cuti Melahirkan</td>
        <td class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti Melahirkan') <span class="text-bold">V</span> @endif--}}
        </td>
    </tr>
    <tr>
        <td>5. Cuti Karena Alasan Penting</td>
        <td class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti Alasan Penting') <span class="text-bold">V</span> @endif--}}
        </td>
        <td>6. Cuti di Luar Tanggungan Negara</td>
        <td class="text-center">
{{--            @if($cuti->jenis_cuti == 'Cuti di Luar Tanggungan Negara') <span class="text-bold">V</span> @endif--}}
        </td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td class="text-bold">III. ALASAN CUTI</td>
    </tr>
    <tr>
        <td>{{ $cuti->alasan_cuti }}</td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="6" class="text-bold">IV. LAMANYA CUTI</td>
    </tr>
    <tr>
        <td width="15%">Selama</td>
        <td width="20%">{{ $cuti->lama_cuti }} (Hari)*</td>
        <td width="15%">mulai tanggal</td>
        <td width="20%">{{ \Carbon\Carbon::parse($cuti->mulai_tanggal)->format('d-m-Y') }}</td>
        <td width="5%">s/d</td>
        <td width="25%">{{ \Carbon\Carbon::parse($cuti->sampai_tanggal)->format('d-m-Y') }}</td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="5" class="text-bold">V. CATATAN CUTI***</td>
    </tr>
    <tr>
        <td colspan="3" width="50%">1. CUTI TAHUNAN</td>
        <td width="35%">2. CUTI BESAR</td>
        <td width="15%"></td>
    </tr>
    <tr>
        <td width="15%" class="text-center">Tahun</td>
        <td width="15%" class="text-center">Sisa</td>
        <td width="20%" class="text-center">Keterangan</td>
        <td>3. CUTI SAKIT</td>
        <td></td>
    </tr>
    <tr>
        <td class="text-center">N-2</td>
        <td class="text-center">{{ $pegawai->sisa_cuti_n_2 }}</td>
        <td></td>
        <td>4. CUTI MELAHIRKAN</td>
        <td></td>
    </tr>
    <tr>
        <td class="text-center">N-1</td>
        <td class="text-center">{{ $pegawai->sisa_cuti_n_1 }}</td>
        <td></td>
        <td>5. CUTI KARENA ALASAN PENTING</td>
        <td></td>
    </tr>
    <tr>
        <td class="text-center">N</td>
        <td class="text-center">{{ $pegawai->sisa_cuti_n }}</td>
        <td></td>
        <td>6. CUTI DI LUAR TANGGUNGAN NEGARA</td>
        <td></td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="3" class="text-bold">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
    </tr>
    <tr>
        <td width="50%" rowspan="2" style="vertical-align: top;">
            {{ $cuti->alamat_selama_cuti }}
        </td>
        <td width="15%">TELP</td>
        <td width="35%">{{ $cuti->telepon_selama_cuti }}</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            Hormat Saya,<br><br>
            @if($cuti->ttd_path)
                <img src="{{ public_path('storage/'.$cuti->ttd_path) }}" width="80" height="50" style="object-fit: contain;"><br>
            @else
                <br><br><br>
            @endif

            ( {{ $pegawai->nama_lengkap }} )<br>
            NIP. {{ $pegawai->nip }}
        </td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="4" class="text-bold">VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
    </tr>
    <tr>
        <td width="25%" class="text-center">DISETUJUI</td>
        <td width="25%" class="text-center">PERUBAHAN</td>
        <td width="25%" class="text-center">DITANGGUHKAN</td>
        <td width="25%" class="text-center">TIDAK DISETUJUI</td>
    </tr>
    <tr>
        <td class="text-center">
            <br>
{{--            @if($cuti->status == 'disetujui' && $cuti->keputusan_pejabat == 'DISETUJUI') V @endif--}}
        </td>
        <td class="text-center">
{{--            @if($cuti->keputusan_pejabat == 'PERUBAHAN') V @endif--}}
        </td>
        <td class="text-center">
{{--            @if($cuti->keputusan_pejabat == 'DITANGGUHKAN') V @endif--}}
        </td>
        <td class="text-center">
{{--            @if($cuti->status == 'ditolak' || $cuti->keputusan_pejabat == 'TIDAK DISETUJUI') V @endif--}}
        </td>
    </tr>
    <tr>
        <td colspan="3" style="vertical-align: middle;">
            @if($cuti->catatan_pejabat)
                Catatan: {{ $cuti->catatan_pejabat }}
            @endif
        </td>
        <td class="text-center">
            <br>
            {{ $atasan['jabatan'] }},<br>
            <br><br><br>
            <span class="text-underline">{{ $atasan['nama'] }}</span><br>
            NIP. {{ $atasan['nip'] }}
        </td>
    </tr>
</table>

<table class="table-data">
    <tr>
        <td colspan="4" class="text-bold">VIII. KEPUTUSAN PEJABAT BERWENANG MEMBERIKAN CUTI**</td>
    </tr>
    <tr>
        <td width="25%" class="text-center">DISETUJUI</td>
        <td width="25%" class="text-center">PERUBAHAN</td>
        <td width="25%" class="text-center">DITANGGUHKAN</td>
        <td width="25%" class="text-center">TIDAK DISETUJUI</td>
    </tr>
    <tr>
        <td class="text-center">
            <br>
{{--            @if($cuti->status == 'disetujui') V @endif--}}
        </td>
        <td class="text-center"></td>
        <td class="text-center"></td>
        <td class="text-center">
{{--            @if($cuti->status == 'ditolak') V @endif--}}
        </td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td class="text-center">
            <br>
            {{ $pejabat['jabatan'] }},<br>
            <br><br><br>
            <span class="text-underline">{{ $pejabat['nama'] }}</span><br>
            NIP. {{ $pejabat['nip'] }}
        </td>
    </tr>
</table>

<div style="font-size: 8pt; margin-top: 5px;">
    Catatan:<br>
    * Coret yang tidak perlu<br>
    ** Pilih salah satu dengan memberi tanda centang (V)<br>
    *** Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti
</div>

</body>
</html>
