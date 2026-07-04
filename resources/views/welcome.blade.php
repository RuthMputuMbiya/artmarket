<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marché digital des œuvres artistiques</title>

    <!-- ===== FONTS ===== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- ===== FONT AWESOME ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ============================================
                   RESET & BASE (Couleurs claires)
                ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f6f3;
            color: #1e1a16;
            min-height: 100vh;
            overflow-x: hidden;
        }

        :root {
            --primary: #6C3CE1;
            --primary-dark: #5A2FC2;
            --primary-light: #8B6CE6;
            --secondary: #E85D75;
            --accent: #E8A838;
            --success: #2ECC71;
            --cream: #f8f6f3;
            --white: #ffffff;
            --dark: #1e1a16;
            --gray: #6b6560;
            --light-gray: #e8e4df;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ============================================
                   NAVIGATION
                ============================================ */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 16px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .navbar .logo span {
            color: var(--primary);
        }

        .navbar .logo i {
            color: var(--primary);
            margin-right: 8px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .nav-btn {
            padding: 10px 24px;
            border-radius: 60px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn-login {
            background: transparent;
            color: var(--gray);
            border: 1px solid var(--light-gray);
        }

        .nav-btn-login:hover {
            background: rgba(108, 60, 225, 0.05);
            color: var(--primary);
            border-color: var(--primary);
        }

        .nav-btn-register {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(108, 60, 225, 0.25);
        }

        .nav-btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(108, 60, 225, 0.35);
        }

        /* ============================================
                   HERO (Couleurs claires)
                ============================================ */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f6f3 0%, #f0ebe4 30%, #e8e0d8 60%, #f0ebe4 100%);
            padding-top: 80px;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(108, 60, 225, 0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 50%, rgba(232, 93, 117, 0.05) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 100%, rgba(232, 168, 56, 0.04) 0%, transparent 40%);
            z-index: 1;
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(108, 60, 225, 0.06);
            animation: float linear infinite;
        }

        .particle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-duration: 20s;
        }
        .particle:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -150px;
            animation-duration: 25s;
            background: rgba(232, 93, 117, 0.04);
        }
        .particle:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-duration: 30s;
            background: rgba(232, 168, 56, 0.04);
        }

        .floating-images {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
        }

        .float-img {
            position: absolute;
            opacity: 0.15;
            animation: float 6s ease-in-out infinite;
            font-size: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 150px;
            background: rgba(108, 60, 225, 0.03);
            border-radius: 50%;
        }

        .float-img:nth-child(1) {
            top: 8%;
            left: 5%;
            animation-delay: 0s;
            font-size: 50px;
        }
        .float-img:nth-child(2) {
            bottom: 12%;
            left: 3%;
            animation-delay: 1.5s;
            font-size: 60px;
        }
        .float-img:nth-child(3) {
            top: 15%;
            right: 5%;
            animation-delay: 3s;
            font-size: 45px;
        }
        .float-img:nth-child(4) {
            bottom: 8%;
            right: 3%;
            animation-delay: 2s;
            font-size: 70px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 40px 20px;
            animation: fadeInUp 1s ease forwards;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(108, 60, 225, 0.08);
            border: 1px solid rgba(108, 60, 225, 0.12);
            border-radius: 50px;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero-badge i {
            margin-right: 8px;
        }

        .hero-title {
            font-size: clamp(48px, 8vw, 88px);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--dark);
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 40%, var(--secondary) 70%, var(--accent) 100%);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 6s ease infinite;
        }

        .hero-title span {
            background: none;
            -webkit-text-fill-color: initial;
            color: var(--accent);
        }

        .hero-subtitle {
            font-size: clamp(18px, 2vw, 24px);
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 40px;
            font-weight: 300;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
            margin-bottom: 60px;
        }

        .btn-primary-hero {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 40px;
            border-radius: 60px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 8px 30px rgba(108, 60, 225, 0.25);
        }

        .btn-primary-hero:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 40px rgba(108, 60, 225, 0.35);
        }

        .btn-secondary-hero {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 16px 40px;
            border-radius: 60px;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            background: var(--white);
            border: 2px solid var(--light-gray);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        .btn-secondary-hero:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            max-width: 700px;
            margin: 0 auto;
            padding-top: 40px;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-label {
            font-size: 13px;
            color: var(--gray);
            font-weight: 400;
            margin-top: 4px;
        }

        /* ============================================
                   DERNIÈRES ŒUVRES (AFFICHAGE AUTOMATIQUE)
                ============================================ */
        .oeuvres-section {
            padding: 80px 20px;
            background: var(--white);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: clamp(30px, 4vw, 48px);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .section-header h2 span {
            color: var(--primary);
        }

        .section-header p {
            color: var(--gray);
            font-size: 18px;
            max-width: 500px;
            margin: 0 auto;
        }

        .oeuvres-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .oeuvre-card {
            background: var(--cream);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .oeuvre-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
            border-color: rgba(108, 60, 225, 0.1);
        }

        .oeuvre-card .image {
            height: 220px;
            overflow: hidden;
            background: var(--light-gray);
            position: relative;
        }

        .oeuvre-card .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .oeuvre-card .image:hover img {
            transform: scale(1.05);
        }

        .oeuvre-card .image .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--primary);
            color: white;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        .oeuvre-card .image .badge-new {
            background: var(--secondary);
        }

        .oeuvre-card .body {
            padding: 20px;
        }

        .oeuvre-card .body .titre {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .oeuvre-card .body .artisan {
            font-size: 14px;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .oeuvre-card .body .prix {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
        }

        .oeuvre-card .body .prix-ancien {
            font-size: 14px;
            color: #E74C3C;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 400;
        }

        .oeuvre-card .footer {
            padding: 12px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .oeuvre-card .footer .stock {
            font-size: 12px;
            color: var(--gray);
        }

        .oeuvre-card .footer .stock .ok {
            color: var(--success);
        }

        .oeuvre-card .footer .btn-view {
            padding: 6px 18px;
            border-radius: 50px;
            background: var(--primary);
            color: white;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .oeuvre-card .footer .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 60, 225, 0.3);
        }

        .btn-voir-plus {
            display: inline-block;
            margin-top: 40px;
            padding: 14px 40px;
            border-radius: 60px;
            background: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(108, 60, 225, 0.25);
        }

        .btn-voir-plus:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(108, 60, 225, 0.35);
        }

        /* ============================================
                   FEATURES
                ============================================ */
        .features-section {
            padding: 80px 20px;
            background: var(--cream);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.04);
            border-color: rgba(108, 60, 225, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            background: rgba(108, 60, 225, 0.06);
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .feature-card p {
            color: var(--gray);
            font-size: 14px;
            line-height: 1.7;
        }

        /* ============================================
                   CTA
                ============================================ */
        .cta-section {
            padding: 80px 20px;
            text-align: center;
            background: linear-gradient(135deg, rgba(108, 60, 225, 0.04), rgba(232, 93, 117, 0.03));
            border-top: 1px solid rgba(0, 0, 0, 0.04);
        }

        .cta-section h2 {
            font-size: clamp(28px, 3vw, 40px);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .cta-section h2 span {
            color: var(--primary);
        }

        .cta-section p {
            color: var(--gray);
            font-size: 18px;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        /* ============================================
                   FOOTER
                ============================================ */
        .footer {
            padding: 40px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.04);
            text-align: center;
            background: var(--white);
        }

        .footer p {
            color: var(--gray);
            font-size: 13px;
        }

        .footer .social-links {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .footer .social-links a {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            border: 1px solid var(--light-gray);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .footer .social-links a:hover {
            color: var(--primary);
            border-color: var(--primary);
            background: rgba(108, 60, 225, 0.05);
            transform: translateY(-3px);
        }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
                flex-direction: column;
                gap: 12px;
            }
            .nav-actions {
                width: 100%;
                justify-content: center;
            }
            .nav-btn {
                padding: 8px 18px;
                font-size: 13px;
            }
            .hero-title {
                font-size: 40px;
            }
            .hero {
                padding-top: 140px;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            .btn-primary-hero, .btn-secondary-hero {
                width: 100%;
                justify-content: center;
                padding: 14px 24px;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
            .oeuvres-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
            .hero-stats {
                grid-template-columns: 1fr 1fr;
            }
            .float-img {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 32px;
            }
            .hero-stats {
                grid-template-columns: 1fr;
            }
            .oeuvres-grid {
                grid-template-columns: 1fr;
            }
            .nav-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- ==========================================
    NAVIGATION
    ========================================== -->
    <nav class="navbar">
        <a href="#" class="logo">
            <i class="fas fa-palette"></i> Marché<span> digital des œuvres artistiques</span>
        </a>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-btn nav-btn-register">
                    <i class="fas fa-chart-simple"></i> <span>Tableau de bord</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-btn nav-btn-login">
                    <i class="fas fa-sign-in-alt"></i> <span>Connexion</span>
                </a>
                <a href="{{ route('register') }}" class="nav-btn nav-btn-register">
                    <i class="fas fa-user-plus"></i> <span>Inscription</span>
                </a>
            @endauth
        </div>
    </nav>

    <!-- ==========================================
    HERO
    ========================================== -->
    <section class="hero">
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="floating-images">
            <div class="float-img">🎨</div>
            <div class="float-img">🖼️</div>
            <div class="float-img">✨</div>
            <div class="float-img">🎭</div>
        </div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-sparkles"></i> Marché Artisant
            </div>
            <h1 class="hero-title">Marché<span></span> digital des œuvres artistiques<br></h1>
            <p class="hero-subtitle">Découvrez des œuvres artistiques. Achetez directement, soutenez la création artistique.</p>
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary-hero"><i class="fas fa-chart-simple"></i> Tableau de bord</a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary-hero"><i class="fas fa-user-plus"></i> S'inscrire</a>
                    <a href="{{ route('login') }}" class="btn-secondary-hero"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
                @endauth
            </div>
            
        </div>
    </section>

  <!-- ==========================================
DERNIÈRES ŒUVRES (AFFICHAGE AUTOMATIQUE)
========================================== -->
<section class="oeuvres-section">
    <div class="section-header">
        <h2>🖼️ Dernières <span>Œuvres artistiques</span></h2>
        <p>Découvrez les dernières créations ajoutées par nos artisans</p>
    </div>

    <div class="oeuvres-grid">
        @forelse($oeuvres ?? [] as $oeuvre)
            <div class="oeuvre-card">
                <div class="image">
                    <!-- ✅ UTILISER image_principale_url POUR AFFICHER L'IMAGE -->
                    <img src="{{ $oeuvre->image_principale_url ?? asset('images/placeholder.jpg') }}" 
                         alt="{{ $oeuvre->titre }}" loading="lazy">
                    
                    @if($oeuvre->created_at->diffInDays(now()) < 7)
                        <span class="badge badge-new"><i class="fas fa-star"></i> Nouveau</span>
                    @endif
                    @if($oeuvre->is_featured)
                        <span class="badge" style="background: #E8A838;"><i class="fas fa-crown"></i> À la une</span>
                    @endif
                    @if($oeuvre->stock > 0)
                        <span class="badge" style="background: #2ECC71; color: white;"><i class="fas fa-check"></i> En stock</span>
                    @else
                        <span class="badge" style="background: #E74C3C; color: white;"><i class="fas fa-times"></i> Rupture</span>
                    @endif
                </div>
                <div class="body">
                    <div class="titre">{{ $oeuvre->titre }}</div>
                    <div class="artisan"><i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan' }}</div>
                    <div>
                        <span class="prix">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}</span>
                        @if($oeuvre->prix_promo)
                            <span class="prix-ancien">{{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix, 0) }}</span>
                        @endif
                    </div>
                </div>
                <div class="footer">
                    <span class="stock">
                        @if($oeuvre->stock > 0)
                            <span class="ok"><i class="fas fa-check-circle"></i> {{ $oeuvre->stock }} en stock</span>
                        @else
                            <span style="color:#E74C3C;"><i class="fas fa-times-circle"></i> Rupture</span>
                        @endif
                    </span>
                    <a href="{{ route('client.oeuvre.show', $oeuvre->slug) }}" class="btn-view">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px 20px;background:var(--white);border-radius:16px;">
                <i class="fas fa-palette" style="font-size:60px;color:var(--light-gray);display:block;margin-bottom:16px;"></i>
                <h3 style="font-size:20px;color:var(--dark);margin-bottom:8px;">Aucune œuvre disponible</h3>
                <p style="color:var(--gray);">Soyez le premier à découvrir les créations de nos artisans.</p>
            </div>
        @endforelse
    </div>

    <div style="text-align:center;">
        <a href="{{ route('client.catalogue') }}" class="btn-voir-plus">
            <i class="fas fa-compass"></i> Voir tout le catalogue
        </a>
    </div>
</section>
    <!-- ==========================================
    FEATURES
    ========================================== -->
    <section class="features-section">
        <div class="section-header">
            <h2>Pourquoi <span>Marché digital des œuvres artistiques</span> ?</h2>
            <p>Une plateforme conçue pour connecter les artisans et les amateurs d'art</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--primary);"><i class="fas fa-palette"></i></div>
                <h3>Œuvres artistiques</h3>
                <p>Des créations originales et authentiques, réalisées par des artisans talentueux du monde entier.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--secondary);"><i class="fas fa-shield-alt"></i></div>
                <h3>Achat Sécurisé</h3>
                <p>Paiement sécurisé et protection de vos données personnelles.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="color: var(--accent);"><i class="fas fa-handshake"></i></div>
                <h3>Relation Directe</h3>
                <p>Échangez directement avec les artisans, discutez de leurs œuvres et de leurs inspirations.</p>
            </div>
        </div>
    </section>

    <!-- ==========================================
    CTA
    ========================================== -->
    <section class="cta-section">
        <h2>Prêt à <span>Rejoindre</span> l'Aventure ?</h2>
        <p>Que vous soyez artisan ou amateur , Marché Artisant est fait pour vous.</p>
        @guest
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px;">
                <a href="{{ route('register') }}" class="btn-primary-hero"><i class="fas fa-user-plus"></i> Devenir membre</a>
                <a href="{{ route('login') }}" class="btn-secondary-hero"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="btn-primary-hero"><i class="fas fa-chart-simple"></i> Accéder à mon espace</a>
        @endguest
    </section>

    <!-- ==========================================
    FOOTER
    ========================================== -->
    <footer class="footer">
        
        <p>&copy; {{ date('Y') }} <strong style="color: var(--primary);">Marché digital des œuvres artistiques</strong>. Tous droits réservés. Fait avec <i class="fas fa-heart" style="color: var(--secondary);"></i></p>
    </footer>

</body>
</html>