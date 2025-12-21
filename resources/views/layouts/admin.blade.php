<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SIM Cuti</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #7c4dff;
            --secondary-color: #ff4081;
            --text-color: #333;
            --bg-color: #f4f7fe;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* --- SIDEBAR MODERN --- */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: var(--primary-gradient);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 25px rgba(0,0,0,0.05);
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-item { list-style: none; margin: 10px 15px; }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .nav-link:hover, .nav-link.active {
            color: #7c4dff;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            font-weight: 600;
        }

        .nav-link i { width: 25px; font-size: 1.1rem; }

        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.5);
            margin: 20px 25px 10px;
            font-weight: 600;
        }

        /* --- CONTENT WRAPPER --- */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* --- CARD STYLING --- */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 25px;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* --- BUTTONS --- */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
        }
        .btn-primary:hover { background: #6a42d9; box-shadow: 0 5px 15px rgba(124, 77, 255, 0.3); }

        .btn-success {
            background: #00b894; border: none; padding: 10px 20px; border-radius: 10px;
        }
        .btn-success:hover { background: #00a383; box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3); }

        /* --- DATATABLES CUSTOM --- */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 5px 10px;
        }
        table.dataTable thead th {
            background-color: #f8f9fc !important;
            color: #555;
            font-weight: 600;
            border-bottom: 2px solid #eee !important;
        }
        table.dataTable tbody td {
            padding: 15px 10px !important;
            vertical-align: middle;
            color: #444;
            border-bottom: 1px solid #f5f5f5;
        }
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 5px;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(var(--sidebar-width) * -1); }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-layer-group"></i> SIM CUTI
    </div>

    <ul class="nav flex-column mt-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>

        <div class="sidebar-heading">Data Master</div>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pegawai.index') ? 'active' : '' }}" href="{{ route('pegawai.index') }}">
                <i class="fas fa-users"></i> Data Pegawai
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('unit-kerja.index') ? 'active' : '' }}" href="{{ route('unit-kerja.index') }}">
                <i class="fas fa-building"></i> Unit Kerja
            </a>
        </li>

        <div class="sidebar-heading">System</div>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                <i class="fas fa-cogs"></i> Pengaturan Instansi
            </a>
        </li>

        <li class="nav-item mt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link bg-danger text-white border-0 w-100 justify-content-start" style="background: rgba(255,255,255,0.1) !important;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>

<div class="main-content">
    <button class="btn btn-primary d-md-none mb-3 shadow-sm" id="sidebarToggle"><i class="fa fa-bars"></i></button>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" style="border-radius: 15px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" style="border-radius: 15px;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Init Sidebar Mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Init All DataTables Automagically
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                search: "Cari Data:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" }
            }
        });
    });
</script>
</body>
</html>
