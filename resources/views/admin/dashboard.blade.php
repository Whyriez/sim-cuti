@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold" style="color: #4a4a4a;">Dashboard Pengajuan</h3>
            <p class="text-muted">Pantau dan proses pengajuan cuti pegawai.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-4 border-0 h-100 position-relative overflow-hidden shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Menunggu Persetujuan</h6>
                        <h2 class="mb-0 fw-bold text-primary">{{ $pengajuan->where('status', 'pending')->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-primary text-white rounded-circle p-3 shadow-sm" style="opacity: 0.9;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 h-100 position-relative overflow-hidden shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Disetujui</h6>
                        <h2 class="mb-0 fw-bold text-success">{{ $pengajuan->where('status', 'disetujui')->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-success text-white rounded-circle p-3 shadow-sm" style="opacity: 0.9;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 h-100 position-relative overflow-hidden shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.8rem;">Ditolak</h6>
                        <h2 class="mb-0 fw-bold text-danger">{{ $pengajuan->where('status', 'ditolak')->count() }}</h2>
                    </div>
                    <div class="icon-shape bg-danger text-white rounded-circle p-3 shadow-sm" style="opacity: 0.9;">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold" style="color: #7c4dff;">
                <i class="fas fa-list me-2"></i> Daftar Pengajuan Terbaru
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable w-100">
                    <thead>
                    <tr>
                        <th class="ps-3">Tanggal Ajuan</th>
                        <th>Pegawai</th>
                        <th>Unit Kerja</th>
                        <th>Jenis Cuti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pengajuan as $p)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->pegawai->nama_lengkap ?? 'Unknown' }}</div>
                                <small class="text-muted" style="font-size: 0.85rem;">{{ $p->pegawai_nip }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    {{ $p->pegawai->unit_kerja->nama_unit ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $p->jenis_cuti }}</span><br>
                                <small class="text-muted">{{ $p->lama_cuti }} Hari</small>
                            </td>
                            <td>
                                @if($p->status == 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> Pending</span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check me-1"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fas fa-times me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalApprove{{ $p->id }}">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>

                                @if($p->status == 'disetujui')
                                    <a href="{{ route('pengajuan.cetak', $p->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3 ms-1" title="Cetak PDF">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($pengajuan as $p)
        <div class="modal fade" id="modalApprove{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border-radius: 15px;">
                    <div class="modal-header border-0 bg-light">
                        <h5 class="modal-title fw-bold text-primary">
                            <i class="fas fa-file-alt me-2"></i> Detail Pengajuan Cuti
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('pengajuan.update', $p->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-muted small text-uppercase">Data Pegawai</h6>
                                    <table class="table table-sm table-borderless small">
                                        <tr><td class="text-muted" width="100">Nama</td><td class="fw-bold">{{ $p->pegawai->nama_lengkap }}</td></tr>
                                        <tr><td class="text-muted">NIP</td><td>{{ $p->pegawai->nip }}</td></tr>
                                        <tr><td class="text-muted">Jabatan</td><td>{{ $p->pegawai->jabatan }}</td></tr>
                                        <tr><td class="text-muted">Unit Kerja</td><td>{{ $p->pegawai->unit_kerja->nama_unit ?? '-' }}</td></tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-muted small text-uppercase">Detail Cuti</h6>
                                    <table class="table table-sm table-borderless small">
                                        <tr><td class="text-muted" width="100">Jenis Cuti</td><td class="fw-bold text-primary">{{ $p->jenis_cuti }}</td></tr>
                                        <tr><td class="text-muted">Lama</td><td>{{ $p->lama_cuti }} Hari</td></tr>
                                        <tr><td class="text-muted">Tanggal</td><td>{{ \Carbon\Carbon::parse($p->mulai_tanggal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($p->sampai_tanggal)->format('d M Y') }}</td></tr>
                                        <tr><td class="text-muted">Alasan</td><td>{{ $p->alasan_cuti }}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-muted small text-uppercase">Kontak Selama Cuti</h6>
                                    <div class="bg-light p-3 rounded border">
                                        <p class="mb-1 small"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> {{ $p->alamat_selama_cuti ?? '-' }}</p>
                                        <p class="mb-0 small"><i class="fas fa-phone me-2 text-secondary"></i> {{ $p->telepon_selama_cuti ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold text-muted small text-uppercase">Bukti Pendukung</h6>
                                    @if($p->file_bukti_path)
                                        <a href="{{ asset('storage/' . $p->file_bukti_path) }}" target="_blank" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                            <i class="fas fa-file-pdf fa-lg"></i> Lihat File Bukti (PDF)
                                        </a>
                                        <small class="text-muted d-block mt-1 text-center">*Klik untuk melihat dokumen</small>
                                    @else
                                        <div class="alert alert-secondary mb-0 py-2 text-center small">
                                            <i class="fas fa-info-circle me-1"></i> Tidak ada bukti dilampirkan
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <hr class="border-secondary opacity-25">

                            <div class="bg-light p-3 rounded border border-primary border-opacity-25">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-gavel me-2"></i> Keputusan Pejabat</h6>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Status Pengajuan</label>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>⏳ PENDING</option>
                                            <option value="disetujui" {{ $p->status == 'disetujui' ? 'selected' : '' }}>✅ DISETUJUI</option>
                                            <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>❌ DITOLAK</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label small fw-bold">Catatan / Alasan</label>
                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Contoh: Kuota cuti mencukupi / Syarat kurang" value="{{ $p->catatan_pejabat }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-save me-2"></i> Simpan Keputusan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
