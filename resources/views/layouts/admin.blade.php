<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Marché Artistiques')</title>

    <!-- ===== FONTS ===== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- ===== FONT AWESOME ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ===== VITE ===== -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================
                   VARIABLES
                ============================================ */
        :root {
            --admin-primary: #6C3CE1;
            --admin-primary-dark: #5A2FC2;
            --admin-primary-light: #8B6CE6;
            --admin-secondary: #FF6B6B;
            --admin-success: #2ECC71;
            --admin-warning: #F39C12;
            --admin-danger: #E74C3C;
            --admin-dark: #2D3436;
            --admin-gray: #95A5A6;
            --admin-light: #F8F9FA;
            --admin-sidebar-width: 280px;
            --admin-header-height: 70px;
        }

        /* ============================================
                   BASE
                ============================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================
                   SIDEBAR
                ============================================ */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1a0a2e 0%, #2d1b4e 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--admin-primary-light);
            border-radius: 10px;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand .logo {
            font-size: 28px;
            font-weight: 800;
            color: white;
            text-decoration: none;
        }

        .sidebar-brand .logo span {
            color: var(--admin-primary-light);
        }

        .sidebar-brand .badge {
            font-size: 10px;
            background: var(--admin-primary);
            padding: 2px 10px;
            border-radius: 50px;
            color: white;
        }

        .sidebar-menu {
            padding: 16px 12px;
        }

        .sidebar-menu .menu-label {
            font-size: 11px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 12px 12px 8px;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 2px;
            font-weight: 500;
            font-size: 14px;
        }

        .sidebar-menu .menu-item:hover {
            background: rgba(255,255,255,0.06);
            color: white;
        }

        .sidebar-menu .menu-item.active {
            background: rgba(108, 60, 225, 0.25);
            color: white;
            box-shadow: inset 3px 0 0 var(--admin-primary-light);
        }

        .sidebar-menu .menu-item i {
            width: 20px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-menu .menu-item .badge-count {
            margin-left: auto;
            background: var(--admin-secondary);
            color: white;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 50px;
            font-weight: 600;
        }

        /* ============================================
                   HEADER
                ============================================ */
        .admin-header {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--admin-sidebar-width);
            height: var(--admin-header-height);
            background: white;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }

        .admin-header .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-header .header-left .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--admin-dark);
            cursor: pointer;
        }

        .admin-header .header-left .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .admin-header .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-header .header-right .search-box {
            position: relative;
        }

        .admin-header .header-right .search-box input {
            padding: 8px 16px 8px 40px;
            border-radius: 50px;
            border: 2px solid #E8ECF1;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
            width: 220px;
            background: var(--admin-light);
        }

        .admin-header .header-right .search-box input:focus {
            border-color: var(--admin-primary);
            background: white;
            width: 280px;
        }

        .admin-header .header-right .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-gray);
        }

        .admin-header .header-right .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .admin-header .header-right .user-profile:hover {
            background: rgba(0,0,0,0.04);
        }

        .admin-header .header-right .user-profile img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--admin-primary-light);
        }

        .admin-header .header-right .user-profile .user-info {
            line-height: 1.2;
        }

        .admin-header .header-right .user-profile .user-info .name {
            font-size: 13px;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .admin-header .header-right .user-profile .user-info .role {
            font-size: 11px;
            color: var(--admin-gray);
        }

        .admin-header .header-right .logout-btn {
            background: none;
            border: none;
            color: var(--admin-gray);
            font-size: 18px;
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 4px 6px;
        }

        .admin-header .header-right .logout-btn:hover {
            color: var(--admin-danger);
        }

        /* ============================================
                   MAIN CONTENT
                ============================================ */
        .admin-main {
            margin-left: var(--admin-sidebar-width);
            margin-top: var(--admin-header-height);
            padding: 24px 32px;
            min-height: calc(100vh - var(--admin-header-height));
        }

        /* ============================================
                   STATS CARDS
                ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            border-left: 4px solid var(--admin-primary);
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .stat-card .stat-icon.purple { background: #E8E0F8; color: var(--admin-primary); }
        .stat-card .stat-icon.orange { background: #FDE8D0; color: var(--admin-warning); }
        .stat-card .stat-icon.green { background: #D4F4E6; color: var(--admin-success); }
        .stat-card .stat-icon.red { background: #FCD4D4; color: var(--admin-danger); }
        .stat-card .stat-icon.blue { background: #D4E8FC; color: #3498DB; }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--admin-dark);
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--admin-gray);
        }

        /* ============================================
                   CARD
                ============================================ */
        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }

        .card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .card .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .card .card-header .card-actions {
            display: flex;
            gap: 8px;
        }

        /* ============================================
                   BOUTONS
                ============================================ */
        .btn-primary {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
        }

        .btn-secondary {
            background: var(--admin-light);
            color: var(--admin-dark);
            border: 2px solid #E8ECF1;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            border-color: var(--admin-primary);
            color: var(--admin-primary);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--admin-success), #27AE60);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--admin-danger), #C0392B);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* ============================================
                   TABLE
                ============================================ */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .table thead {
            background: var(--admin-light);
            border-radius: 12px;
        }

        .table th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--admin-dark);
            border-bottom: 2px solid #E8ECF1;
        }

        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid #E8ECF1;
            color: var(--admin-dark);
        }

        .table tbody tr:hover {
            background: rgba(108, 60, 225, 0.03);
        }

        /* ============================================
                   BADGE
                ============================================ */
        .badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success { background: #D4F4E6; color: #27AE60; }
        .badge-danger { background: #FCD4D4; color: #E74C3C; }
        .badge-warning { background: #FDE8D0; color: #F39C12; }
        .badge-info { background: #D4E8FC; color: #3498DB; }
        .badge-primary { background: #E8E0F8; color: var(--admin-primary); }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-header {
                left: 0;
                padding: 0 16px;
            }

            .admin-header .header-left .menu-toggle {
                display: block;
            }

            .admin-main {
                margin-left: 0;
                padding: 16px;
            }

            .admin-header .header-right .search-box input {
                width: 150px;
            }

            .admin-header .header-right .search-box input:focus {
                width: 180px;
            }
        }

        @media (max-width: 576px) {
            .admin-header .header-right .search-box {
                display: none;
            }

            .admin-header .header-right .user-profile .user-info {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .admin-main {
                padding: 12px;
            }
        }

        /* ===== OVERLAY ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            Marché <span>Artistiques</span>
        </a>
        <span class="badge">Admin</span>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Navigation</div>

        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Tableau de bord
        </a>

        <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Utilisateurs
        </a>

        <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>

        <div class="menu-label">Gestion</div>

        <a href="{{ route('admin.oeuvres.index') }}" class="menu-item {{ request()->routeIs('admin.oeuvres*') ? 'active' : '' }}">
            <i class="fas fa-palette"></i> Oeuvres
        </a>
      


        <form action="{{ route('logout') }}" method="POST" style="margin-top: 4px;">
            @csrf
            <button type="submit" class="menu-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.6);">
                <i class="fas fa-sign-out-alt"></i> Deconnexion
            </button>
        </form>
    </nav>
</aside>

<!-- ===== OVERLAY ===== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== HEADER ===== -->
<header class="admin-header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
    </div>

    <div class="header-right">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher...">
        </div>

        <a href="{{ route('profile.edit') }}" class="user-profile">
            @php
                $avatarUrl = auth()->user()->photo_profil 
                    ? asset('storage/' . auth()->user()->photo_profil) 
                    : asset('images/default-avatar.png');
            @endphp
            <img src="{{ $avatarUrl }}" alt="Avatar">
            <div class="user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">{{ auth()->user()->role->nom ?? 'Admin' }}</div>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn" title="Deconnexion">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</header>

<!-- ===== MAIN CONTENT ===== -->
<main class="admin-main">
    @yield('content')
</main>

<!-- ===== SCRIPTS ===== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('menuToggle');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>