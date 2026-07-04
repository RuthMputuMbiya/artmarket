<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Artisan - ArtMarket')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================
                   VARIABLES
                ============================================ */
        :root {
            --artisan-primary: #086472;
            --artisan-primary-dark: #27AE60;
            --artisan-primary-light: #82E0AA;
            --artisan-secondary: #FF6B6B;
            --artisan-success: #2ECC71;
            --artisan-warning: #F39C12;
            --artisan-danger: #E74C3C;
            --artisan-dark: #2D3436;
            --artisan-gray: #95A5A6;
            --artisan-light: #F8F9FA;
            --artisan-sidebar-width: 260px;
            --artisan-header-height: 70px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================
                   SIDEBAR ARTISAN
                ============================================ */
        .artisan-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--artisan-sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #155069 0%, #01213c 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .artisan-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .artisan-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .artisan-sidebar::-webkit-scrollbar-thumb {
            background: var(--artisan-primary-light);
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
            font-size: 24px;
            font-weight: 800;
            color: white;
            text-decoration: none;
        }

        .sidebar-brand .logo span {
            color: var(--artisan-primary-light);
        }

        .sidebar-brand .badge {
            font-size: 10px;
            background: var(--artisan-primary);
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
            background: rgba(46, 204, 113, 0.2);
            color: white;
            box-shadow: inset 3px 0 0 var(--artisan-primary);
        }

        .sidebar-menu .menu-item i {
            width: 20px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-menu .menu-item .badge-count {
            margin-left: auto;
            background: var(--artisan-secondary);
            color: white;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 50px;
            font-weight: 600;
        }

        /* ============================================
                   HEADER ARTISAN
                ============================================ */
        .artisan-header {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--artisan-sidebar-width);
            height: var(--artisan-header-height);
            background: white;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 999;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }

        .artisan-header .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .artisan-header .header-left .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--artisan-dark);
            cursor: pointer;
        }

        .artisan-header .header-left .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--artisan-dark);
        }

        .artisan-header .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .artisan-header .header-right .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            transition: background 0.3s ease;
        }

        .artisan-header .header-right .user-profile:hover {
            background: rgba(0,0,0,0.04);
        }

        .artisan-header .header-right .user-profile img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--artisan-primary);
        }

        .artisan-header .header-right .user-profile .user-info {
            line-height: 1.2;
        }

        .artisan-header .header-right .user-profile .user-info .name {
            font-size: 13px;
            font-weight: 600;
            color: var(--artisan-dark);
        }

        .artisan-header .header-right .user-profile .user-info .role {
            font-size: 11px;
            color: var(--artisan-gray);
        }

        /* ============================================
                   MAIN CONTENT
                ============================================ */
        .artisan-main {
            margin-left: var(--artisan-sidebar-width);
            margin-top: var(--artisan-header-height);
            padding: 24px 32px;
            min-height: calc(100vh - var(--artisan-header-height));
        }

        /* ============================================
                   STATS CARDS ARTISAN
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
            border-left: 4px solid var(--artisan-primary);
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

        .stat-card .stat-icon.green { background: #D4F4E6; color: var(--artisan-primary); }
        .stat-card .stat-icon.orange { background: #FDE8D0; color: var(--artisan-warning); }
        .stat-card .stat-icon.blue { background: #D4E8FC; color: #3498DB; }
        .stat-card .stat-icon.purple { background: #E8E0F8; color: #6C3CE1; }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--artisan-dark);
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--artisan-gray);
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
            color: var(--artisan-dark);
        }

        /* ============================================
                   BOUTONS
                ============================================ */
        .btn-primary {
            background: linear-gradient(135deg, var(--artisan-primary), var(--artisan-primary-dark));
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
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.35);
        }

        .btn-secondary {
            background: var(--artisan-light);
            color: var(--artisan-dark);
            border: 2px solid #E8ECF1;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--artisan-primary);
            color: var(--artisan-primary);
        }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 992px) {
            .artisan-sidebar {
                transform: translateX(-100%);
            }

            .artisan-sidebar.open {
                transform: translateX(0);
            }

            .artisan-header {
                left: 0;
                padding: 0 16px;
            }

            .artisan-header .header-left .menu-toggle {
                display: block;
            }

            .artisan-main {
                margin-left: 0;
                padding: 16px;
            }
        }

        @media (max-width: 576px) {
            .artisan-header .header-right .user-profile .user-info {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .artisan-main {
                padding: 12px;
            }
        }

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

    <!-- ===== SIDEBAR ARTISAN ===== -->
    <aside class="artisan-sidebar" id="artisanSidebar">
        <div class="sidebar-brand">
            <a href="{{ route('artisan.dashboard') }}" class="logo">
                🎨Marché <span> Artistiques</span>
            </a>
            <span class="badge">Artisan</span>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Navigation</div>

            <a href="{{ route('artisan.dashboard') }}" class="menu-item {{ request()->routeIs('artisan.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Tableau de bord
            </a>

            <a href="{{ route('artisan.oeuvres.index') }}" class="menu-item {{ request()->routeIs('artisan.oeuvres*') ? 'active' : '' }}">
                <i class="fas fa-palette"></i> Mes œuvres
            
            </a>

            <a href="{{ route('artisan.oeuvres.create') }}" class="menu-item">
                <i class="fas fa-plus-circle"></i> Ajouter une œuvre
            </a>

            <div class="menu-label">Gestion</div>

            <a href="{{ route('artisan.commandes.index') }}" class="menu-item {{ request()->routeIs('artisan.commandes*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Commandes
              
            </a>

            <a href="{{ route('artisan.paiements.index') }}" class="menu-item {{ request()->routeIs('artisan.paiements*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Paiements
            </a>

         

            <a href="{{ route('artisan.statistiques') }}" class="menu-item {{ request()->routeIs('artisan.statistiques') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Statistiques
            </a>

            <div class="menu-label">Paramètres</div>

            <a href="{{ route('artisan.profil.edit') }}" class="menu-item">
                <i class="fas fa-user-edit"></i> Mon profil
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin-top: 4px;">
                @csrf
                <button type="submit" class="menu-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.6);">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </nav>
    </aside>

    <!-- ===== OVERLAY ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

   <!-- ===== HEADER ===== -->
<header class="artisan-header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">@yield('page-title', 'Mon Atelier')</h1>
    </div>

    <div class="header-right">
        <!-- ===== PROFIL ARTISAN (CORRIGÉ) ===== -->
        <div class="user-profile">
            <img src="{{ auth()->user()->photo_profil_url ?? asset('images/default-avatar.png') }}" alt="Avatar">
            <div class="user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">Artisan</div>
            </div>
        </div>
    </div>
</header>
    <!-- ===== MAIN ===== -->
    <main class="artisan-main">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('artisanSidebar');
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