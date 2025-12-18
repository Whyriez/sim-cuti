<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Permohonan Cuti</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ... (CSS Lama biarkan saja) ... */
        :root {
            --primary-color: #7c4dff;
            --secondary-color: #ff4081;
            --header-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-color: #f4f6f9;
        }

        body {
            background: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: #333;
            min-height: 100vh;
        }

        /* HEADER STYLE */
        header {
            background: var(--header-gradient);
            padding: 60px 20px 80px;
            text-align: center;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        header h2 { font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }

        .back-btn {
            position: absolute; top: 20px; left: 20px;
            color: white; text-decoration: none; font-weight: 600;
            background: rgba(255,255,255,0.2); padding: 8px 20px;
            border-radius: 30px; backdrop-filter: blur(5px); transition: 0.3s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.4); }

        /* FORM CARD */
        .main-container {
            max-width: 1000px; margin: -50px auto 50px;
            padding: 0 20px; position: relative; z-index: 2;
        }

        .form-card {
            background: white; border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px; border: 1px solid #eee;
        }

        .section-title {
            color: var(--primary-color); font-weight: 600; font-size: 1.1rem;
            border-left: 5px solid var(--secondary-color);
            padding-left: 15px; margin: 30px 0 20px;
            display: flex; align-items: center;
        }

        label { font-weight: 500; color: #555; margin-bottom: 8px; font-size: 0.95rem; }

        .form-control, .form-select {
            padding: 12px; border-radius: 10px; border: 1px solid #ddd; font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(124, 77, 255, 0.15);
        }
        .form-control[readonly] { background-color: #f8f9fa; }

        .btn-submit {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none; padding: 15px; border-radius: 50px;
            font-size: 1.1rem; font-weight: 600;
            box-shadow: 0 5px 15px rgba(124, 77, 255, 0.3); transition: 0.3s;
        }
        .btn-submit:hover { transform: translateY(-3px); opacity: 0.9; }

        /* --- STYLE BARU: VALIDASI ERROR (RING MERAH) --- */
        .input-error {
            border: 2px solid #ff4081 !important;
            box-shadow: 0 0 8px rgba(255, 64, 129, 0.4) !important;
            animation: shake 0.3s;
        }
        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        /* Auto Uppercase Nama */
        #nama_display { text-transform: uppercase; }
    </style>
</head>
<body>

<header>
    <a href="{{ route('landing') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Kembali</a>
    <h2>Formulir Permohonan Cuti</h2>
    <p style="opacity: 0.8;">Lengkapi data di bawah ini dengan benar</p>
</header>

<div class="main-container">
    <form id="formCuti" class="form-card" enctype="multipart/form-data">
        @csrf

        <div class="section-title" style="margin-top: 0;">
            <i class="fas fa-user-tie me-2"></i> Data Pegawai
        </div>

        <div class="mb-4">
            <label>Nomor Induk Pegawai (NIP)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                <input type="text"
                       name="nip"
                       id="nip"
                       class="form-control"
                       placeholder="Masukkan NIP"
                       required
                       maxlength="18"
                       inputmode="numeric"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <button type="button" class="btn btn-secondary" onclick="cekNip()">
                    <i class="fas fa-search"></i> Cek Data
                </button>
            </div>
            <div id="loading_nip" class="text-primary mt-2 d-none">
                <small><i class="fas fa-spinner fa-spin"></i> Mencari data...</small>
            </div>
            <small class="text-muted ms-1">* Masukkan 18 digit NIP tanpa spasi</small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nama Pegawai (KAPITAL)</label>
                <input type="text" id="nama_display" name="nama_lengkap_display" class="form-control" readonly placeholder="Otomatis terisi">
            </div>
            <div class="col-md-6 mb-3">
                <label>Jabatan</label>
                <input type="text" id="jabatan" class="form-control" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Unit Kerja</label>
                <input type="text" id="unit_kerja" class="form-control" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Masa Kerja</label>
                <input type="text" id="masa_kerja" class="form-control" readonly>
            </div>
            <div class="section-divider mt-4"></div>
            <div class="section-title">
                <i class="fas fa-user-check me-2"></i> Data Atasan Langsung
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Nama Atasan</label>
                    <input type="text" id="nama_atasan_display" class="form-control bg-light" readonly placeholder="-">
                </div>
                <div class="col-md-4 mb-3">
                    <label>NIP Atasan</label>
                    <input type="text" id="nip_atasan_display" class="form-control bg-light" readonly placeholder="-">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Jabatan Atasan</label>
                    <input type="text" id="jabatan_atasan_display" class="form-control bg-light" readonly placeholder="-">
                </div>
            </div>

            <input type="hidden" name="nama_atasan_langsung" id="nama_atasan">
            <input type="hidden" name="nip_atasan_langsung" id="nip_atasan">
            <input type="hidden" name="jabatan_atasan_langsung" id="jabatan_atasan">
        </div>

        <input type="hidden" name="nama_atasan_langsung" id="nama_atasan">
        <input type="hidden" name="nip_atasan_langsung" id="nip_atasan">
        <input type="hidden" name="jabatan_atasan_langsung" id="jabatan_atasan">

        <div class="section-divider mt-4"></div>
        <div class="section-title">
            <i class="fas fa-calendar-alt me-2"></i> Detail Pengajuan Cuti
        </div>

        <div class="alert alert-warning d-none" id="quota_warning">
            <div class="d-flex">
                <div class="me-3 mt-1">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <strong>Sisa Kuota Cuti Tahunan Anda:</strong>
                    <ul class="mb-1 ps-3 mt-1" style="list-style-type: circle;">
                        <li>N (Tahun Ini) : <span id="val_n" class="fw-bold">0</span> hari</li>
                        <li>N-1 (Tahun Lalu) : <span id="val_n1" class="fw-bold">0</span> hari</li>
                        <li>N-2 (2 Tahun Lalu) : <span id="val_n2" class="fw-bold">0</span> hari</li>
                    </ul>
                    <div class="mt-1">
                        Total Sisa: <span id="sisa_quota_text" class="fw-bold text-danger" style="font-size: 1.1em;">0</span> Hari
                    </div>
                    <small class="text-muted d-block mt-1">(Hari Sabtu & Minggu tidak akan mengurangi kuota)</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Jenis Cuti <span class="text-danger">*</span></label>
                <select name="jenis_cuti" id="jenis_cuti" class="form-select" required>
                    <option value="" disabled selected>Pilih jenis cuti...</option>
                    <option value="Cuti Tahunan">Cuti Tahunan</option>
                    <option value="Cuti Besar">Cuti Besar</option>
                    <option value="Cuti Sakit">Cuti Sakit</option>
                    <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                    <option value="Cuti Alasan Penting">Cuti Alasan Penting</option>
                    <option value="Cuti di Luar Tanggungan Negara">Cuti di Luar Tanggungan Negara</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Sisa Kuota (Tahunan)</label>
                <input type="text" id="sisa_cuti" class="form-control bg-light" readonly placeholder="-" style="font-weight: bold; color: #ff4081;">
            </div>
            <div class="col-md-4 mb-3">
                <label>Lama Cuti (Hari Kerja)</label>
                <input type="number" name="lama_cuti" id="lama_cuti" class="form-control" placeholder="0" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="mulai_tanggal" id="mulai_tanggal" class="form-control flatpickr" required disabled>
                <small class="text-muted">Cek NIP dulu untuk membuka tanggal</small>
            </div>
            <div class="col-md-6 mb-3">
                <label>Sampai Dengan <span class="text-danger">*</span></label>
                <input type="date" name="sampai_tanggal" id="sampai_tanggal" class="form-control flatpickr" required disabled>
            </div>
        </div>

        <div class="mb-3">
            <label>Alasan Cuti <span class="text-danger">*</span></label>
            <textarea name="alasan_cuti" id="alasan_cuti" class="form-control" rows="3" placeholder="Jelaskan alasan pengajuan cuti..." required></textarea>
        </div>

        <div class="mb-3 p-3 bg-light rounded border border-dashed">
            <label class="d-block mb-2 fw-bold text-primary">
                <i class="fas fa-paperclip"></i> Upload Bukti Pendukung (PDF)
            </label>
            <input type="file" name="file_bukti" id="file_bukti" class="form-control" accept="application/pdf">
            <small class="text-muted d-block mt-1">
                * Wajib untuk <b>Cuti Sakit</b> (Surat Dokter).
            </small>
        </div>

        <div class="row">
            <div class="col-md-8 mb-3">
                <label>Alamat Selama Cuti <span class="text-danger">*</span></label>
                <input type="text" name="alamat_selama_cuti" id="alamat_selama_cuti" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>No. Telepon / WA <span class="text-danger">*</span></label>
                <input type="text" name="telepon_selama_cuti" id="telepon_selama_cuti" class="form-control" required>
            </div>
        </div>

        <div class="section-title">
            <i class="fas fa-file-signature me-2"></i> Tanda Tangan & Foto
        </div>

        <div class="row">
            <div class="col-md-6 text-center mb-4">
                <label class="mb-2 fw-bold">Tanda Tangan Digital <span class="text-danger">*</span></label>
                <div class="media-box" style="border: 2px dashed #ccc; padding: 10px; background: #fafafa;">
                    <canvas id="canvasTTD" width="400" height="240" style="width: 100%;"></canvas>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 mt-2" id="clearTTD">
                    <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                </button>
                <input type="hidden" name="ttd_image" id="ttd_image">
            </div>

            <div class="col-md-6 text-center mb-4">
                <label class="mb-2 fw-bold">Foto Selfie (Verifikasi) <span class="text-danger">*</span></label>
                <div class="media-box" id="camera_container" style="border: 2px dashed #ccc; padding: 10px; background: #fafafa; min-height: 240px; display:flex; justify-content:center; align-items:center;">
                    <div id="my_camera"></div>
                    <div id="results" class="d-none"></div>
                </div>

                <div class="mt-2 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-primary" onclick="takeSnapshot()" id="btn_snap">
                        <i class="fas fa-camera"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-sm btn-danger d-none" onclick="resetCamera()" id="btn_reset">
                        <i class="fas fa-redo"></i> Reset Foto
                    </button>
                </div>
                <input type="hidden" name="foto_selfie" id="foto_selfie">
            </div>
        </div>

        <hr class="my-4">

        <button type="submit" class="btn btn-submit w-100 text-white shadow-lg">
            <i class="fas fa-paper-plane me-2"></i> AJUKAN PERMOHONAN CUTI
        </button>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    // GLOBAL VARIABLES
    let currentQuota = 0; // Menyimpan quota cuti tahunan dari server

    // 1. SETUP FLATPICKR
    const fpMulai = flatpickr("#mulai_tanggal", { dateFormat: "Y-m-d", minDate: "today", locale: { firstDayOfWeek: 1 } });
    const fpSampai = flatpickr("#sampai_tanggal", { dateFormat: "Y-m-d", minDate: "today", locale: { firstDayOfWeek: 1 } });

    // 2. LOGIC HITUNG HARI KERJA (NO WEEKEND)
    function hitungHariKerja(startStr, endStr) {
        if (!startStr || !endStr) return 0;

        let startDate = new Date(startStr);
        let endDate = new Date(endStr);
        let count = 0;

        // Loop per hari
        while (startDate <= endDate) {
            let dayOfWeek = startDate.getDay();
            // 0 = Sunday, 6 = Saturday. Skip them.
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                count++;
            }
            startDate.setDate(startDate.getDate() + 1);
        }
        return count;
    }

    $("#mulai_tanggal, #sampai_tanggal").change(function () {
        let tglMulai = $("#mulai_tanggal").val();
        let tglSelesai = $("#sampai_tanggal").val();

        if (tglMulai && tglSelesai) {
            if (tglMulai > tglSelesai) {
                Swal.fire("Error", "Tanggal selesai tidak boleh lebih awal dari tanggal mulai!", "error");
                $("#sampai_tanggal").val('');
                $("#lama_cuti").val('');
                return;
            }

            // Panggil fungsi hitung (exclude weekend)
            let durasi = hitungHariKerja(tglMulai, tglSelesai);
            $("#lama_cuti").val(durasi);

            // Cek vs Quota jika Cuti Tahunan
            let jenis = $("#jenis_cuti").val();
            if (jenis === 'Cuti Tahunan' && currentQuota > 0) {
                if (durasi > currentQuota) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Kuota Tidak Cukup!',
                        text: `Anda mengajukan ${durasi} hari kerja, tapi kuota sisa ${currentQuota} hari.`
                    });
                    // Opsional: tambahkan class error
                    $("#lama_cuti").addClass('input-error');
                } else {
                    $("#lama_cuti").removeClass('input-error');
                }
            }
        }
    });

    // 3. LOGIC JENIS CUTI & QUOTA
    $("#jenis_cuti").change(function() {
        let jenis = $(this).val();
        if (jenis === 'Cuti Tahunan') {
            $("#quota_warning").removeClass('d-none');
            $("#sisa_cuti").val(currentQuota + " Hari");
            $("#sisa_quota_text").text(currentQuota);
        } else {
            $("#quota_warning").addClass('d-none');
            $("#sisa_cuti").val("-"); // Tidak ada kuota untuk cuti lain
            $("#lama_cuti").removeClass('input-error');
        }
    });

    // 4. CEK NIP (Update UI & Logic)
    function cekNip() {
        let nip = $('#nip').val();
        if(!nip) {
            $('#nip').addClass('input-error');
            return;
        } else {
            $('#nip').removeClass('input-error');
        }

        $('#loading_nip').removeClass('d-none');

        $.ajax({
            url: "/api/pegawai/" + nip,
            type: "GET",
            success: function(response) {
                $('#loading_nip').addClass('d-none');
                if(response.status === 'success') {
                    let p = response.data;

                    // 1. Isi Data Pegawai
                    $('#nama_display').val(p.nama_lengkap.toUpperCase());
                    $('#jabatan').val(p.jabatan);
                    $('#unit_kerja').val(p.unit_kerja ? p.unit_kerja.nama_unit : '-');
                    $('#masa_kerja').val(p.masa_kerja || '-');



                    // 2. Isi Data Atasan Langsung (DARI RELASI UNIT KERJA)
                    if(p.unit_kerja) {
                        // Tampilkan di Input Readonly
                        $('#nama_atasan_display').val(p.unit_kerja.nama_kepala || '-');
                        $('#nip_atasan_display').val(p.unit_kerja.nip_kepala || '-');
                        $('#jabatan_atasan_display').val(p.unit_kerja.jabatan_kepala || '-');

                        // Isi Input Hidden (Opsional)
                        $('#nama_atasan').val(p.unit_kerja.nama_kepala);
                        $('#nip_atasan').val(p.unit_kerja.nip_kepala);
                        $('#jabatan_atasan').val(p.unit_kerja.jabatan_kepala);
                    } else {
                        // Jika tidak ada unit kerja
                        $('#nama_atasan_display').val('Belum diset');
                        $('#nip_atasan_display').val('-');
                        $('#jabatan_atasan_display').val('-');
                    }

                    // Simpan Quota Efektif
                    currentQuota = p.quota_tahunan_efektif || 0;

                    if (p.rincian_quota) {
                        $('#val_n').text(p.rincian_quota.n);
                        $('#val_n1').text(p.rincian_quota.n1);
                        $('#val_n2').text(p.rincian_quota.n2);
                    }

                    // Update Text Total
                    $("#sisa_quota_text").text(currentQuota);
                    $("#sisa_cuti").val(currentQuota + " Hari");

                    // Buka Akses Tanggal
                    $('#mulai_tanggal').prop('disabled', false);
                    $('#sampai_tanggal').prop('disabled', false);

                    $("#jenis_cuti").trigger('change');

                    Swal.fire({ icon: 'success', title: 'Data Ditemukan', timer: 1000, showConfirmButton: false });
                }
            },
            error: function() {
                $('#loading_nip').addClass('d-none');
                Swal.fire("Gagal", "NIP Tidak Ditemukan", "error");
                // Reset Semua Field
                $('#nama_display').val('');
                $('#unit_kerja').val('');
                $('#nama_atasan_display').val(''); // Reset atasan
                $('#nip_atasan_display').val('');
                $('#jabatan_atasan_display').val('');

                currentQuota = 0;
                $('#mulai_tanggal').prop('disabled', true);
            }
        });
    }

    // 5. SIGNATURE & WEBCAM
    var canvas = document.getElementById('canvasTTD');
    var signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgba(255, 255, 255, 0)' });

    document.getElementById('clearTTD').addEventListener('click', function () { signaturePad.clear(); });

    Webcam.set({ width: 320, height: 240, image_format: 'jpeg', jpeg_quality: 90 });
    try { Webcam.attach('#my_camera'); } catch(e) { $('#camera_container').html('Kamera tidak terdeteksi'); }

    function takeSnapshot() {
        Webcam.snap(function(data_uri) {
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'" class="img-fluid rounded"/>';
            document.getElementById('foto_selfie').value = data_uri;
            $('#results').removeClass('d-none'); $('#my_camera').addClass('d-none');
            $('#btn_snap').addClass('d-none'); $('#btn_reset').removeClass('d-none');
        });
    }
    function resetCamera() {
        $('#foto_selfie').val('');
        $('#results').addClass('d-none'); $('#my_camera').removeClass('d-none');
        $('#btn_snap').removeClass('d-none'); $('#btn_reset').addClass('d-none');
    }

    // 6. SUBMIT FORM DENGAN VALIDASI HIGHLIGHT
    $('#formCuti').on('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        let firstError = null;

        // List field wajib
        const requiredFields = [
            '#nip', '#jenis_cuti', '#mulai_tanggal', '#sampai_tanggal',
            '#alasan_cuti', '#alamat_selama_cuti', '#telepon_selama_cuti'
        ];

        // Reset Error
        $('.form-control, .form-select').removeClass('input-error');

        // Cek Input Biasa
        requiredFields.forEach(selector => {
            let el = $(selector);
            if(el.val() === '' || el.val() === null) {
                el.addClass('input-error');
                isValid = false;
                if(!firstError) firstError = el;
            }
        });

        // Cek TTD
        if (signaturePad.isEmpty()) {
            Swal.fire("Peringatan", "Tanda tangan wajib diisi", "warning");
            isValid = false;
        } else {
            $('#ttd_image').val(signaturePad.toDataURL());
        }

        // Cek Selfie
        if ($('#foto_selfie').val() == '') {
            Swal.fire("Peringatan", "Foto selfie wajib diambil", "warning");
            isValid = false;
        }

        // Jika Error, scroll ke elemen pertama yg error
        if (!isValid) {
            if(firstError) {
                $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                firstError.focus();
            }
            return;
        }

        // Submit Ajax
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('pengajuan.store') }}",
            type: "POST",
            data: formData,
            contentType: false, processData: false,
            beforeSend: function() {
                Swal.fire({ title: 'Mengirim...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            },
            success: function(res) {
                Swal.fire("Berhasil!", res.message, "success").then(() => {
                    window.location.href = "{{ route('landing') }}";
                });
            },
            error: function(xhr) {
                let msg = "Terjadi kesalahan sistem.";
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire("Gagal", msg, "error");
            }
        });
    });
</script>
</body>
</html>
