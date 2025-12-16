<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Cuti - Kemenag Kota Gorontalo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <style>
        :root {
            --primary-color: #7c4dff;
            --secondary-color: #ff4081;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --header-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background: #f4f6f9;
            min-height: 100vh;
            color: #333;
        }

        /* --- HEADER SECTION --- */
        header {
            background: var(--header-gradient);
            padding: 80px 20px 60px;
            text-align: center;
            color: white;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: -50px; /* Overlap effect */
            position: relative;
            z-index: 1;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 12px 35px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .primary-btn {
            background: white;
            color: var(--primary-color);
        }
        .primary-btn:hover {
            transform: translateY(-3px);
            background: #f8f9fa;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .secondary-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
            backdrop-filter: blur(5px);
        }
        .secondary-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: white;
            transform: translateY(-3px);
        }

        /* --- MAIN CONTENT CARD --- */
        .container {
            padding: 0 20px 40px;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .info-card {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .info-title {
            color: #4a4a4a;
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: 600;
            text-align: center;
        }

        .info-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 40px;
            font-size: 1rem;
        }

        /* Section Titles inside Card */
        .section-header {
            display: flex;
            align-items: center;
            margin: 40px 0 20px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
        }
        .section-header::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e0e0e0;
            margin-left: 15px;
        }

        /* Jenis Cuti Grid */
        .jenis-cuti-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            list-style: none;
        }

        .jenis-cuti-list li {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
            transition: transform 0.2s;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }
        .jenis-cuti-list li:hover {
            transform: translateX(5px);
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .jenis-cuti-list li i {
            margin-right: 10px;
            color: var(--secondary-color);
        }

        /* Periode Cuti (N, N-1) */
        .periode-cuti {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .periode-item {
            background: linear-gradient(135deg, #ffffff 0%, #f3f4f6 100%);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #eee;
            transition: transform 0.3s;
        }
        .periode-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-color: var(--primary-color);
        }
        .periode-item strong {
            font-size: 2.5rem;
            display: block;
            background: -webkit-linear-gradient(var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }
        .periode-item p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        /* Kontak Section */
        .kontak-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            text-align: center;
        }
        .kontak-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            list-style: none;
        }
        .kontak-list li {
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            font-size: 0.95rem;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 0.9rem;
            margin-top: 20px;
        }

        /* --- LOGIN MODAL --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn { from {opacity: 0} to {opacity: 1} }

        .modal-content {
            background-color: #fff;
            margin: 10vh auto;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            position: relative;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            transform: translateY(0);
            animation: slideUp 0.3s;
        }
        @keyframes slideUp { from {transform: translateY(50px)} to {transform: translateY(0)} }

        .close {
            position: absolute; right: 25px; top: 20px;
            font-size: 28px; color: #aaa; cursor: pointer; transition: 0.2s;
        }
        .close:hover { color: #333; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #555; }
        .form-group input {
            width: 100%; padding: 12px 15px;
            border: 1px solid #ddd; border-radius: 10px;
            font-size: 1rem; transition: 0.3s;
        }
        .form-group input:focus {
            border-color: var(--primary-color); outline: none;
            box-shadow: 0 0 0 3px rgba(124, 77, 255, 0.1);
        }

        .login-submit-btn {
            width: 100%; padding: 12px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white; border: none; border-radius: 10px;
            font-size: 1rem; font-weight: 600; cursor: pointer;
            transition: 0.3s;
        }
        .login-submit-btn:hover { opacity: 0.9; transform: translateY(-2px); }

        /* Responsive */
        @media (max-width: 768px) {
            header h1 { font-size: 1.8rem; }
            .info-card { padding: 30px 20px; }
            .periode-cuti { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <h1>Selamat Datang di<br>Formulir Cuti Online</h1>
    <div class="button-container">
        <a href="{{ route('pengajuan.form') }}" class="action-btn primary-btn">
            <i class="fas fa-edit"></i> Ajukan Cuti
        </a>

        <button onclick="openLoginModal()" class="action-btn secondary-btn">
            <i class="fas fa-user-lock"></i> LOGIN PETUGAS
        </button>
    </div>
</header>

<div class="container">
    <div class="info-card">
        <h2 class="info-title">Persiapan Sebelum Mengisi Formulir</h2>
        <p class="info-subtitle">
            Pastikan Anda telah berkonsultasi dengan bagian Kepegawaian terkait hak cuti Anda.
        </p>

        <div class="section-header">Jenis Cuti Yang Tersedia</div>
        <ul class="jenis-cuti-list">
            <li><i class="fas fa-check-circle"></i> Cuti Tahunan</li>
            <li><i class="fas fa-check-circle"></i> Cuti Besar</li>
            <li><i class="fas fa-check-circle"></i> Cuti Sakit</li>
            <li><i class="fas fa-check-circle"></i> Cuti Melahirkan</li>
            <li><i class="fas fa-check-circle"></i> Cuti Alasan Penting</li>
            <li><i class="fas fa-check-circle"></i> Cuti di Luar Tanggungan</li>
        </ul>

        <div class="section-header">Periode Perhitungan Cuti</div>
        <div class="periode-cuti">
            <div class="periode-item">
                <strong>N</strong>
                <p>Cuti Tahun Berjalan</p>
            </div>
            <div class="periode-item">
                <strong>N-1</strong>
                <p>Sisa Cuti 1 Tahun Lalu</p>
            </div>
            <div class="periode-item">
                <strong>N-2</strong>
                <p>Sisa Cuti 2 Tahun Lalu</p>
            </div>
        </div>

        <div class="kontak-box">
            <h3 style="margin-bottom: 15px; font-size: 1.2rem;">Kontak Kepegawaian Kemenag Kota Gorontalo</h3>
            <ul class="kontak-list">
                <li><i class="fas fa-user"></i> Novan</li>
                <li><i class="fas fa-user"></i> Yulin</li>
                <li><i class="fas fa-user"></i> Sri Vemi Lamatenggo</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Kementerian Agama Kota Gorontalo. All rights reserved.</p>
    </div>
</div>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLoginModal()">&times;</span>
        <div class="text-center mb-4">
            <h3 style="color: var(--primary-color); margin-bottom: 5px;">Login Petugas</h3>
            <p style="color: #888; font-size: 0.9rem;">Silakan masuk untuk mengelola data cuti</p>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; border: 1px solid #fca5a5;">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="admin@gmail.com" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="login-submit-btn">Masuk Dashboard</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("loginModal");

    function openLoginModal() {
        modal.style.display = "block";
        // Disable scroll background
        document.body.style.overflow = "hidden";
    }

    function closeLoginModal() {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            closeLoginModal();
        }
    }

    // Auto open modal jika ada error login dari Laravel
    @if($errors->any())
    openLoginModal();
    @endif
</script>
</body>
</html>
