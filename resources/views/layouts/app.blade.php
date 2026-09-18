{{--
==============================================================================
Tujuan: Layout utama aplikasi Portal Guru (Sidebar, Topbar, Content, Flash Alert).
Dipakai Oleh: Seluruh view dashboard Admin dan Guru (@extends('layouts.app'))
Dependensi: Blade Templating Engine, Auth facade
Fungsi Utama: Render kerangka HTML, styling UI terintegrasi, dan navigasi adaptif role
Side Effect: Menampilkan layout UI dan identitas pengguna yang sedang login
==============================================================================
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Guru') - Sistem Informasi Akademik</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-dark: #1e293b;
            --accent: #0284c7;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: #0f172a;
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            letter-spacing: -0.025em;
        }

        .sidebar-brand span.icon {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 16px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-menu .menu-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 12px 12px 6px;
            font-weight: 600;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.15s ease;
            margin-bottom: 4px;
        }

        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .sidebar-menu li a.active {
            background: #2563eb;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .user-info .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info .user-role {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: capitalize;
        }

        /* Main Container */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            height: 64px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Page Content */
        .content {
            padding: 32px;
            flex-grow: 1;
        }

        /* Card Component */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .card-header h3 {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .card-body {
            padding: 24px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); }

        /* Stat Card */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
        }

        th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; }
        .badge-info    { background: #e0f2fe; color: #0369a1; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background: var(--primary-light);
            color: #ffffff;
        }
        .btn-primary:hover { background: #2563eb; }

        .btn-secondary {
            background: #ffffff;
            color: var(--text-main);
            border-color: var(--border-color);
        }
        .btn-secondary:hover { background: #f8fafc; }

        .btn-danger {
            background: #ffffff;
            color: var(--danger);
            border-color: #fecaca;
        }
        .btn-danger:hover { background: #fee2e2; }

        .btn-sm {
            padding: 4px 10px;
            font-size: 0.8rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            background: #ffffff;
            color: var(--text-main);
            transition: border-color 0.15s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .form-text {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-error {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Pagination */
        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        /* Print styling */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .content { padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar no-print">
        <div class="sidebar-brand">
            <span class="icon">🎓</span>
            <span>Portal Guru</span>
        </div>

        <ul class="sidebar-menu">
            @if(Auth::check() && Auth::user()->isAdmin())
                <div class="menu-category">Menu Administrator</div>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        📊 <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.teachers.index') }}" class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                        👨‍🏫 <span>Kelola Guru</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.classrooms.index') }}" class="{{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}">
                        🏫 <span>Kelola Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                        📚 <span>Mata Pelajaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.assignments.index') }}" class="{{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
                        🔗 <span>Penugasan Guru</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        🎒 <span>Kelola Siswa</span>
                    </a>
                </li>
            @elseif(Auth::check() && Auth::user()->isGuru())
                <div class="menu-category">Menu Guru</div>
                <li>
                    <a href="{{ route('guru.dashboard') }}" class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        📊 <span>Kelas & Mapel</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.attendance.index') }}" class="{{ request()->routeIs('guru.attendance.*') ? 'active' : '' }}">
                        📋 <span>Presensi Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guru.grades.index') }}" class="{{ request()->routeIs('guru.grades.*') ? 'active' : '' }}">
                        📝 <span>Penilaian (UH & Ujian)</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                <span class="user-role">
                    <span class="badge {{ Auth::user()->isAdmin() ? 'badge-info' : 'badge-success' }}">
                        {{ Auth::user()->role ?? 'guest' }}
                    </span>
                </span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Logout" style="padding: 6px 10px;">
                    🚪
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="topbar no-print">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-actions">
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <span>⚠️ {{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="display: block;">
                    <strong style="display: block; margin-bottom: 4px;">Terjadi kesalahan pengisian data:</strong>
                    <ul style="padding-left: 20px; font-size: 0.85rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
