@extends('layouts.admin')

@section('title', 'Pengaturan Instansi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold" style="color: #4a4a4a;">Pengaturan Instansi</h3>
            <p class="text-muted">Kelola data kop surat dan pejabat penandatangan.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i> Form Data Instansi</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="small text-uppercase fw-bold text-muted mb-2">Identitas Kantor</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-building text-primary"></i></span>
                                <input type="text" name="nama_instansi" class="form-control border-start-0 ps-0" value="{{ $setting->nama_instansi }}" placeholder="Contoh: Kementerian Agama Kota Gorontalo" required style="height: 50px;">
                            </div>
                            <div class="form-text">Nama ini akan muncul di Kop Surat Formulir Cuti.</div>
                        </div>

                        <hr class="my-4" style="border-style: dashed; opacity: 0.2;">

                        <div class="mb-4">
                            <label class="small text-uppercase fw-bold text-muted mb-2">Pejabat Berwenang (Penandatangan)</label>

                            <div class="alert alert-light border-start border-4 border-info shadow-sm p-3 mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle text-info fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Informasi Penting</strong>
                                        <p class="mb-0 small text-muted">Pejabat ini adalah yang akan muncul di bagian tanda tangan paling bawah (Mengetahui/Menyetujui) pada formulir cuti.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Nama Lengkap & Gelar</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" name="nama_kepala" class="form-control" value="{{ $setting->nama_kepala }}" placeholder="Nama Pejabat">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">NIP</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-id-badge"></i></span>
                                        <input type="text" name="nip_kepala" class="form-control" value="{{ $setting->nip_kepala }}" placeholder="Nomor Induk Pegawai">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Jabatan</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-briefcase"></i></span>
                                        <input type="text" name="jabatan_kepala" class="form-control" value="{{ $setting->jabatan_kepala }}" placeholder="Contoh: Kepala Kantor">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary shadow py-3 fw-bold" style="border-radius: 10px; background: linear-gradient(to right, #667eea, #764ba2); border: none;">
                                <i class="fas fa-save me-2"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px; z-index: 1;">
                <div class="card border-0 shadow-sm bg-light" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-center text-secondary"><i class="fas fa-eye me-2"></i> Live Preview</h5>
                        <p class="text-center text-muted small mb-4">Simulasi tampilan di file PDF</p>

                        <div class="bg-white p-4 shadow-sm mx-auto position-relative" style="min-height: 400px; width: 100%; font-family: 'Times New Roman', serif; border: 1px solid #e0e0e0;">
                            <div class="position-absolute top-0 end-0 bg-light" style="width: 20px; height: 20px; border-bottom-left-radius: 5px; box-shadow: -2px 2px 2px rgba(0,0,0,0.05);"></div>

                            <div class="text-center border-bottom border-dark pb-2 mb-4">
                                <h5 class="fw-bold text-uppercase mb-1" style="font-size: 14px; letter-spacing: 1px;">
                                    {{ $setting->nama_instansi ?? '[NAMA INSTANSI]' }}
                                </h5>
                                <span class="small fst-italic">Formulir Cuti Pegawai</span>
                            </div>

                            <div style="color: #ccc; font-size: 10px; line-height: 1.5;">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>

                            <div class="mt-5 text-center float-end" style="width: 100%;">
                                <p class="mb-0" style="font-size: 12px;">Gorontalo, {{ date('d M Y') }}</p>
                                <p class="fw-bold mb-5" style="font-size: 12px;">{{ $setting->jabatan_kepala ?? 'Kepala Kantor' }}</p>

                                <br>

                                <p class="mb-0 fw-bold text-decoration-underline text-uppercase" style="font-size: 12px;">
                                    {{ $setting->nama_kepala ?? '[NAMA PEJABAT]' }}
                                </p>
                                <p class="mb-0" style="font-size: 12px;">NIP. {{ $setting->nip_kepala ?? '................' }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
