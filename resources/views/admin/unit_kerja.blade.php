@extends('layouts.admin')

@section('title', 'Manajemen Unit Kerja')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold" style="color: #4a4a4a;">Data Unit Kerja</h3>
            <p class="text-muted">Kelola struktur organisasi dan pejabat penilai</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fas fa-plus me-2"></i> Tambah Unit
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable w-100">
                    <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Unit Kerja</th>
                        <th>Kepala Unit (Pejabat Penilai)</th>
                        <th>Status Data</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($units as $index => $u)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light text-primary rounded p-2 me-3">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $u->nama_unit }}</span>
                                </div>
                            </td>
                            <td>
                                @if($u->nama_kepala)
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $u->nama_kepala }}</span>
                                        <span class="small text-muted"><i class="fas fa-id-badge me-1"></i> {{ $u->nip_kepala }}</span>
                                        <span
                                            class="badge bg-light text-secondary border mt-1 w-auto align-self-start">{{ $u->jabatan_kepala }}</span>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Belum diset</span>
                                @endif
                            </td>
                            <td>
                                @if($u->nama_kepala)
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1"><i
                                            class="fas fa-check-circle me-1"></i> Lengkap</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1"><i
                                            class="fas fa-exclamation-circle me-1"></i> Kosong</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#editUnit{{ $u->id }}">
                                    <i class="fas fa-user-edit me-1"></i> Atur Pejabat
                                </button>

                                <form action="{{ route('unit-kerja.destroy', $u->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus Unit Kerja ini?\n\nPegawai yang ada di unit ini akan menjadi Tanpa Unit.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm"
                                            title="Hapus Unit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editUnit{{ $u->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius: 15px;">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fas fa-building me-2 text-primary"></i> {{ $u->nama_unit }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('unit-kerja.update', $u->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-light border mb-3">
                                                <small class="text-muted d-block">Pejabat ini akan menjadi penanda
                                                    tangan formulir cuti untuk pegawai di unit ini.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Nama
                                                    Pejabat</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                                    <input type="text" name="nama_kepala" class="form-control"
                                                           value="{{ $u->nama_kepala }}" required
                                                           placeholder="Nama Lengkap & Gelar">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label
                                                    class="form-label fw-bold small text-uppercase text-muted">NIP</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-id-card"></i></span>
                                                    <input type="text" name="nip_kepala" class="form-control"
                                                           value="{{ $u->nip_kepala }}" required
                                                           placeholder="Nomor Induk Pegawai">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Jabatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i
                                                            class="fas fa-briefcase"></i></span>
                                                    <input type="text" name="jabatan_kepala" class="form-control"
                                                           value="{{ $u->jabatan_kepala }}" required
                                                           placeholder="Contoh: Kepala Madrasah">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal
                                            </button>
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
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Unit Kerja Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('unit-kerja.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info border mb-3">
                            <small class="d-block"><i class="fas fa-info-circle me-1"></i> Masukkan nama unit kerja.
                                Data pejabat bisa diisi sekarang atau menyusul.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nama Unit Kerja <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                                <input type="text" name="nama_unit" class="form-control" required
                                       placeholder="Contoh: Tata Usaha / Madrasah...">
                            </div>
                        </div>

                        <hr class="my-4">
                        <p class="small text-muted fw-bold mb-3">Data Kepala Unit (Opsional)</p>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Nama Pejabat</label>
                            <input type="text" name="nama_kepala" class="form-control"
                                   placeholder="Nama Lengkap & Gelar">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">NIP</label>
                            <input type="text" name="nip_kepala" class="form-control" placeholder="NIP Pejabat">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Jabatan</label>
                            <input type="text" name="jabatan_kepala" class="form-control"
                                   placeholder="Contoh: Kepala Madrasah">
                        </div>

                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
