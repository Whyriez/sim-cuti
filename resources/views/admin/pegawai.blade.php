@extends('layouts.admin')

@section('title', 'Data Pegawai')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold" style="color: #4a4a4a;">Data Pegawai</h3>
            <p class="text-muted">Kelola data master pegawai dan struktur</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fas fa-plus me-2"></i> Tambah
            </button>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="fas fa-file-csv me-2"></i> Import
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable w-100">
                    <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Jabatan</th>
                        <th>Unit Kerja</th>
                        <th>Sisa Cuti</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pegawais as $p)
                        <tr>
                            <td class="fw-medium">{{ $p->nip }}</td>
                            <td>
                                <div class="fw-bold">{{ $p->nama_lengkap }}</div>
                            </td>
                            <td>{{ $p->jabatan ?? '-' }}</td>
                            <td>
                                @if($p->unit_kerja)
                                    <span class="badge bg-light text-primary border">{{ $p->unit_kerja->nama_unit }}</span>
                                @else
                                    <span class="badge bg-light text-muted border">Belum Set</span>
                                @endif
                            </td>
                            <td><span class="badge bg-info text-white rounded-pill px-3">{{ $p->sisa_cuti_n }} Hari</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light text-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->nip }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('pegawai.destroy', $p->nip) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger shadow-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit{{ $p->nip }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content" style="border-radius: 15px;">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">Edit Pegawai: {{ $p->nama_lengkap }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('pegawai.update', $p->nip) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="small text-muted mb-1">Nama Lengkap</label>
                                                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $p->nama_lengkap }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small text-muted mb-1">Jabatan</label>
                                                    <input type="text" name="jabatan" class="form-control" value="{{ $p->jabatan }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="small text-muted mb-1">Unit Kerja</label>
                                                    <select name="unit_kerja_id" class="form-select select2-edit" style="width: 100%">
                                                        <option value="">-- Pilih Unit --</option>
                                                        @foreach(\App\Models\UnitKerja::all() as $u)
                                                            <option value="{{ $u->id }}" {{ $p->unit_kerja_id == $u->id ? 'selected' : '' }}>
                                                                {{ $u->nama_unit }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small text-muted mb-1">Masa Kerja</label>
                                                    <input type="text" name="masa_kerja" class="form-control" value="{{ $p->masa_kerja }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="small text-muted mb-1">Telepon</label>
                                                <input type="text" name="telepon" class="form-control" value="{{ $p->telepon }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="small text-muted mb-1">Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="2">{{ $p->alamat }}</textarea>
                                            </div>

                                            <div class="p-3 bg-light border rounded mb-3">
                                                <label class="fw-bold text-primary mb-2" style="font-size: 0.9rem;">
                                                    <i class="fas fa-cog me-1"></i> Konfigurasi Sisa Cuti
                                                </label>
                                                <div class="row">
                                                    <div class="col-md-4 mb-2">
                                                        <label class="small text-muted mb-1">Tahun Ini (N)</label>
                                                        <input type="number" name="sisa_cuti_n" class="form-control" value="{{ $p->sisa_cuti_n }}" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="small text-muted mb-1">Tahun Lalu (N-1)</label>
                                                        <input type="number" name="sisa_cuti_n_1" class="form-control" value="{{ $p->sisa_cuti_n_1 ?? 0 }}">
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="small text-muted mb-1">2 Thn Lalu (N-2)</label>
                                                        <input type="number" name="sisa_cuti_n_2" class="form-control" value="{{ $p->sisa_cuti_n_2 ?? 0 }}">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog modal-lg"> <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Pegawai Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pegawai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="nip"
                                       class="form-control"
                                       required
                                       placeholder="Contoh: 19800101..."
                                       maxlength="18"
                                       inputmode="numeric"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <small class="text-muted">Wajib 18 digit angka</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" required placeholder="Nama beserta gelar">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Guru Ahli Pertama">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Kerja</label>
                                <select name="unit_kerja_id" class="form-select select2-create" style="width: 100%">
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach(\App\Models\UnitKerja::all() as $u)
                                        <option value="{{ $u->id }}">{{ $u->nama_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Masa Kerja</label>
                                <input type="text" name="masa_kerja" class="form-control" placeholder="Contoh: 10 Tahun 2 Bulan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" name="telepon" class="form-control" placeholder="08...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap pegawai..."></textarea>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12"><label class="fw-bold text-primary">Konfigurasi Kuota Cuti</label></div>

                            <div class="col-md-4">
                                <label>Sisa N (Tahun Ini)</label>
                                <input type="number" name="sisa_cuti_n" id="edit_sisa_n" class="form-control" value="12" required>
                                <small class="text-muted">Default: 12</small>
                            </div>

                            <div class="col-md-4">
                                <label>Sisa N-1 (Thn Lalu)</label>
                                <input type="number" name="sisa_cuti_n_1" id="edit_sisa_n1" class="form-control" value="0">
                                <small class="text-muted">Sisa tahun lalu</small>
                            </div>

                            <div class="col-md-4">
                                <label>Sisa N-2 (2 Thn Lalu)</label>
                                <input type="number" name="sisa_cuti_n_2" id="edit_sisa_n2" class="form-control" value="0">
                                <small class="text-muted">Sisa 2 tahun lalu</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImport" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content" style="border-radius: 15px;">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-file-csv me-2"></i> Import Pegawai</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-cloud-upload-alt text-success" style="font-size: 3rem;"></i>
                            <p class="mt-2 text-muted">Upload file CSV Anda di sini</p>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="file_csv" class="form-control" required accept=".csv">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success w-100">Proses Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // 1. Inisialisasi untuk Modal Tambah
            $('.select2-create').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalCreate'),
                placeholder: '-- Pilih Unit Kerja --',
                allowClear: true
            });

            // 2. Inisialisasi untuk Modal Edit (Looping)
            // Kita loop setiap elemen agar dropdownParent-nya sesuai dengan modal masing-masing
            $('.select2-edit').each(function() {
                $(this).select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $(this).closest('.modal'),
                    placeholder: '-- Pilih Unit Kerja --',
                    allowClear: true
                });
            });
        });
    </script>
@endsection
