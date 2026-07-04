<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client - ArtMarket')</title>

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
            --client-primary: #6C3CE1;
            --client-primary-dark: #5A2FC2;
            --client-primary-light: #8B6CE6;
            --client-secondary: #FF6B6B;
            --client-success: #2ECC71;
            --client-warning: #F39C12;
            --client-danger: #E74C3C;
            --client-dark: #2D3436;
            --client-gray: #95A5A6;
            --client-light: #F8F9FA;
            --client-header-height: 70px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================
                   HEADER CLIENT
                ============================================ */
        .client-header {
            position: sticky;
            top: 0;
            background: white;
            padding: 0 16px;
            height: var(--client-header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }

        .client-header .logo {
            font-size: 22px;
            font-weight: 800;
            color: var(--client-primary);
            text-decoration: none;
            white-space: nowrap;
        }

        .client-header .logo span {
            color: var(--client-secondary);
        }

        .client-header .header-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .client-header .header-nav .nav-link {
            color: var(--client-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: color 0.3s ease;
            padding: 6px 0;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
        }

        .client-header .header-nav .nav-link:hover {
            color: var(--client-primary);
        }

        .client-header .header-nav .nav-link.active {
            color: var(--client-primary);
            border-bottom-color: var(--client-primary);
        }

        .client-header .header-nav .search-box {
            position: relative;
            display: none;
        }

        .client-header .header-nav .search-box input {
            padding: 8px 16px 8px 40px;
            border-radius: 50px;
            border: 2px solid #E8ECF1;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
            width: 180px;
            background: var(--client-light);
        }

        .client-header .header-nav .search-box input:focus {
            border-color: var(--client-primary);
            background: white;
            width: 220px;
        }

        .client-header .header-nav .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--client-gray);
        }

        .client-header .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .client-header .header-actions .icon-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 18px;
            color: var(--client-gray);
            cursor: pointer;
            transition: color 0.3s ease;
            padding: 4px 6px;
        }

        .client-header .header-actions .icon-btn:hover {
            color: var(--client-primary);
        }

        .client-header .header-actions .icon-btn .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--client-danger);
            color: white;
            font-size: 9px;
            font-weight: 600;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .client-header .header-actions .user-profile {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 2px 8px 2px 2px;
            border-radius: 50px;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .client-header .header-actions .user-profile:hover {
            background: rgba(0,0,0,0.04);
        }

        .client-header .header-actions .user-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--client-primary-light);
        }

        .client-header .header-actions .user-profile .user-name {
            font-size: 12px;
            font-weight: 500;
            color: var(--client-dark);
            display: none;
        }

        .client-header .header-actions .auth-buttons {
            display: flex;
            gap: 6px;
        }

        .client-header .header-actions .auth-buttons .btn-primary,
        .client-header .header-actions .auth-buttons .btn-secondary {
            padding: 6px 14px;
            font-size: 12px;
            border-radius: 50px;
        }

        .client-header .header-actions .auth-buttons .btn-secondary {
            background: transparent;
            border: 1px solid #E8ECF1;
        }

        /* ============================================
                   MAIN CONTENT
                ============================================ */
        .client-main {
            padding: 16px;
            max-width: 1400px;
            margin: 0 auto;
            min-height: calc(100vh - var(--client-header-height));
        }

        /* ============================================
                   BOUTONS
                ============================================ */
        .btn-primary {
            background: linear-gradient(135deg, var(--client-primary), var(--client-primary-dark));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
            color: white;
        }

        .btn-primary:active {
            transform: scale(0.97);
        }

        .btn-secondary {
            background: white;
            color: var(--client-dark);
            border: 2px solid #E8ECF1;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .btn-secondary:hover {
            border-color: var(--client-primary);
            color: var(--client-primary);
        }

        .btn-success {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46,204,113,0.35);
            color: white;
        }

        .btn-danger {
            background: var(--client-danger);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .btn-danger:hover {
            background: #C0392B;
            transform: translateY(-2px);
            color: white;
        }

        /* ============================================
                   ALERTES
                ============================================ */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(46,204,113,0.15);
            color: #2ECC71;
            border: 1px solid rgba(46,204,113,0.2);
        }

        .alert-error {
            background: rgba(231,76,60,0.15);
            color: #E74C3C;
            border: 1px solid rgba(231,76,60,0.2);
        }

        .alert-warning {
            background: rgba(243,156,18,0.15);
            color: #F39C12;
            border: 1px solid rgba(243,156,18,0.2);
        }

        /* ============================================
                   MOBILE MENU
                ============================================ */
        .mobile-menu-toggle {
            display: block;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--client-dark);
            cursor: pointer;
            padding: 4px 8px;
        }

        .mobile-nav {
            display: none;
            flex-direction: column;
            background: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-top: 8px;
            position: absolute;
            top: var(--client-header-height);
            left: 12px;
            right: 12px;
            z-index: 999;
        }

        .mobile-nav.open {
            display: flex;
        }

        .mobile-nav .nav-link {
            padding: 12px 16px;
            color: var(--client-dark);
            text-decoration: none;
            font-weight: 500;
            border-radius: 10px;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-nav .nav-link:hover {
            background: rgba(108, 60, 225, 0.05);
            color: var(--client-primary);
        }

        .mobile-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* ============================================
                   RESPONSIVE
                ============================================ */

        /* Tablettes et petits ecrans */
        @media (min-width: 768px) {
            .client-header {
                padding: 0 24px;
            }

            .client-header .logo {
                font-size: 26px;
            }

            .client-header .header-nav .search-box {
                display: block;
            }

            .client-header .header-actions .user-profile .user-name {
                display: inline;
            }

            .mobile-menu-toggle {
                display: none;
            }

            .client-main {
                padding: 24px 32px;
            }

            .client-header .header-actions .icon-btn {
                font-size: 20px;
            }

            .client-header .header-actions .user-profile img {
                width: 36px;
                height: 36px;
            }
        }

        /* Grands ecrans */
        @media (min-width: 1024px) {
            .client-header .header-nav .nav-link {
                font-size: 14px;
                gap: 16px;
            }

            .client-header .header-nav .search-box input {
                width: 200px;
            }

            .client-header .header-nav .search-box input:focus {
                width: 260px;
            }

            .btn-primary, .btn-secondary, .btn-success, .btn-danger {
                padding: 12px 28px;
                font-size: 14px;
            }
        }

        /* Tres petits ecrans */
        @media (max-width: 480px) {
            .client-header .logo {
                font-size: 18px;
            }

            .client-header .header-actions .auth-buttons .btn-primary,
            .client-header .header-actions .auth-buttons .btn-secondary {
                padding: 4px 10px;
                font-size: 10px;
            }

            .client-header .header-actions .icon-btn {
                font-size: 16px;
            }

            .client-header .header-actions .user-profile img {
                width: 28px;
                height: 28px;
            }

            .client-main {
                padding: 10px;
            }

            .alert {
                font-size: 12px;
                padding: 10px 12px;
            }

            .btn-primary, .btn-secondary, .btn-success, .btn-danger {
                padding: 8px 16px;
                font-size: 12px;
                width: 100%;
            }

            .mobile-nav .nav-link {
                font-size: 14px;
                padding: 10px 14px;
            }
        }

        /* Mobile avec clavier */
        @media (max-width: 768px) and (orientation: landscape) {
            .client-header {
                height: 56px;
            }
            :root {
                --client-header-height: 56px;
            }
            .client-header .logo {
                font-size: 18px;
            }
            .client-header .header-actions .user-profile img {
                width: 26px;
                height: 26px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background: #1a1a2e;
            }
            .client-header {
                background: #1a1a2e;
                box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            }
            .client-header .logo {
                color: var(--client-primary-light);
            }
            .client-header .header-nav .nav-link {
                color: #ccc;
            }
            .client-header .header-nav .nav-link.active {
                color: var(--client-primary-light);
            }
            .client-header .header-actions .icon-btn {
                color: #999;
            }
            .client-header .header-actions .user-profile .user-name {
                color: #ccc;
            }
            .client-header .header-nav .search-box input {
                background: #2a2a3e;
                border-color: #3a3a4e;
                color: #ccc;
            }
            .mobile-nav {
                background: #1a1a2e;
            }
            .mobile-nav .nav-link {
                color: #ccc;
            }
            .mobile-nav .nav-link:hover {
                background: rgba(108, 60, 225, 0.15);
            }
            .btn-secondary {
                background: #2a2a3e;
                color: #ccc;
                border-color: #3a3a4e;
            }
            .btn-secondary:hover {
                border-color: var(--client-primary-light);
                color: var(--client-primary-light);
            }
        }
    </style>
</head>
<body>

<!-- ===== HEADER CLIENT ===== -->
<header class="client-header">
    <a href="{{ route('welcome') }}" class="logo">
        Marché<span> Artistiques</span>
    </a>

    <nav class="header-nav">
        <a href="{{ route('client.catalogue') }}" class="nav-link {{ request()->routeIs('client.catalogue*') ? 'active' : '' }}">
            <i class="fas fa-compass"></i> Explorer
        </a>
        <a href="{{ route('client.commandes.index') }}" class="nav-link {{ request()->routeIs('client.commandes*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Commandes
        </a>
        <a href="{{ route('client.favoris.index') }}" class="nav-link {{ request()->routeIs('client.favoris*') ? 'active' : '' }}">
            <i class="fas fa-heart"></i> Favoris
        </a>

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher une oeuvre..." id="searchInput">
        </div>
    </nav>

    <div class="header-actions">
        @auth
            <a href="{{ route('client.panier.index') }}" class="icon-btn">
                <i class="fas fa-shopping-bag"></i>
                <span class="badge" id="panierBadge">0</span>
            </a>

            <!-- ===== NOTIFICATIONS SUPPRIMEES ===== -->
            <!-- Le bouton notification a été enlevé -->

            <!-- ===== PROFIL CLIENT ===== -->
            <a href="{{ route('profile.edit') }}" class="user-profile">
                @php
                    $avatarUrl = auth()->user()->photo_profil 
                        ? asset('storage/' . auth()->user()->photo_profil) 
                        : asset('images/default-avatar.png');
                @endphp
                <img src="{{ $avatarUrl }}" alt="Avatar">
                <span class="user-name">{{ auth()->user()->name }}</span>
            </a>

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--client-gray);font-size:18px;cursor:pointer;padding:4px 6px;">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        @else
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn-secondary">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary">Inscription</a>
            </div>
        @endauth

        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<!-- ===== MOBILE NAV ===== -->
<div class="mobile-nav" id="mobileNav">
    <a href="{{ route('client.catalogue') }}" class="nav-link"><i class="fas fa-compass"></i> Explorer</a>
    <a href="{{ route('client.commandes.index') }}" class="nav-link"><i class="fas fa-box"></i> Commandes</a>
    <a href="{{ route('client.favoris.index') }}" class="nav-link"><i class="fas fa-heart"></i> Favoris</a>
    <a href="{{ route('client.panier.index') }}" class="nav-link"><i class="fas fa-shopping-bag"></i> Panier</a>
    <a href="{{ route('profile.edit') }}" class="nav-link"><i class="fas fa-user"></i> Mon profil</a>
    
    @auth
        <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
            @csrf
            <button type="submit" style="width:100%;padding:12px 16px;background:rgba(231,76,60,0.1);color:#E74C3C;border:1px solid rgba(231,76,60,0.2);border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:12px;font-family:'Poppins',sans-serif;">
                <i class="fas fa-sign-out-alt"></i> Deconnexion
            </button>
        </form>
    @endauth
</div>

<!-- ===== MAIN ===== -->
<main class="client-main">
    <!-- ALERTES -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        </div>
    @endif

    @yield('content')
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== MOBILE MENU =====
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const mobileNav = document.getElementById('mobileNav');

        if (toggleBtn && mobileNav) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileNav.classList.toggle('open');
                this.innerHTML = mobileNav.classList.contains('open') 
                    ? '<i class="fas fa-times"></i>' 
                    : '<i class="fas fa-bars"></i>';
            });

            // Fermer en cliquant à l'extérieur
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.client-header') && !e.target.closest('.mobile-nav')) {
                    mobileNav.classList.remove('open');
                    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        }

        // ===== RECHERCHE =====
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && this.value.trim().length > 0) {
                    window.location.href = '/client/catalogue?search=' + encodeURIComponent(this.value.trim());
                }
            });
        }

        // ===== PANIER BADGE =====
        function updatePanierBadge() {
            const badge = document.getElementById('panierBadge');
            if (badge) {
                fetch('{{ route("client.panier.count") }}')
                    .then(response => response.json())
                    .then(data => {
                        badge.textContent = data.count || 0;
                        badge.style.display = data.count > 0 ? 'flex' : 'none';
                    })
                    .catch(() => {});
            }
        }

        updatePanierBadge();

        // ===== NOTIFICATIONS SUPPRIMEES =====
        // La fonction updateNotifBadge a été supprimée

        // ===== TOUCH GESTURES POUR MOBILE =====
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeDistance = touchEndX - touchStartX;
            if (Math.abs(swipeDistance) > 100) {
                if (swipeDistance > 0) {
                    if (mobileNav && mobileNav.classList.contains('open')) {
                        mobileNav.classList.remove('open');
                        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                }
            }
        }

        // ===== RENDRE LES BOUTONS FULL WIDTH SUR MOBILE =====
        function handleButtonResponsive() {
            const isMobile = window.innerWidth <= 480;
            const buttons = document.querySelectorAll('.actions .btn-primary, .actions .btn-secondary, .actions .btn-success, .actions .btn-danger');
            
            buttons.forEach(btn => {
                if (isMobile) {
                    btn.style.width = '100%';
                    btn.style.justifyContent = 'center';
                } else {
                    btn.style.width = 'auto';
                }
            });
        }

        handleButtonResponsive();
        window.addEventListener('resize', handleButtonResponsive);
    });
</script>

@stack('scripts')

</body>
</html>