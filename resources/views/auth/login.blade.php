@extends('layouts.app')

@section('title', 'Connexion - ArtMarket')

@section('content')

<style>
    /* ===== CONTAINER ===== */
    .auth-container {
        display: flex;
        max-width: 1100px;
        width: 100%;
        min-height: 600px;
        border-radius: 30px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.08);
        animation: fadeInUp 0.8s ease;
        margin: 40px auto;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }

    /* ===== LEFT SIDE ===== */
    .auth-left {
        flex: 1;
        padding: 50px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .auth-logo {
        font-size: 28px;
        font-weight: 800;
        color: #1e1a16;
        margin-bottom: 10px;
        text-decoration: none;
    }

    .auth-logo span {
        color: #6C3CE1;
    }

    .auth-subtitle {
        color: #6b6560;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .auth-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e1a16;
        margin-bottom: 8px;
    }

    .auth-title span {
        color: #6C3CE1;
    }

    .auth-description {
        color: #6b6560;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .btn-back-home {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6b6560;
        font-size: 13px;
        text-decoration: none;
        transition: color 0.3s ease;
        margin-bottom: 20px;
    }

    .btn-back-home:hover {
        color: #6C3CE1;
    }

    /* ===== FORMULAIRE ===== */
    .auth-form .form-group {
        margin-bottom: 20px;
    }

    .auth-form label {
        display: block;
        color: #1e1a16;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .auth-form .input-group {
        position: relative;
    }

    .auth-form .input-group i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b6560;
        font-size: 16px;
    }

    .auth-form input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border-radius: 14px;
        border: 2px solid #e8e4df;
        background: #f8f6f3;
        color: #1e1a16;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        outline: none;
    }

    .auth-form input:focus {
        border-color: #6C3CE1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(108, 60, 225, 0.08);
    }

    .auth-form input::placeholder {
        color: #6b6560;
    }

    /* ===== OPTIONS ===== */
    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0 30px;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* ===== CHECKBOX "SE SOUVENIR DE MOI" ===== */
    .auth-options .remember {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1e1a16;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        transition: color 0.3s ease;
        user-select: none;
    }

    .auth-options .remember:hover {
        color: #6C3CE1;
    }

    /* ✅ STYLE DE LA CASE À COCHER */
    .auth-options .remember input[type="checkbox"] {
        position: relative;
        width: 20px;
        height: 20px;
        min-width: 20px;
        min-height: 20px;
        accent-color: #6C3CE1;
        cursor: pointer;
        border: 2px solid #d1cbc4;
        border-radius: 6px;
        background: white;
        appearance: none;
        -webkit-appearance: none;
        transition: all 0.25s ease;
        outline: none;
        margin: 0;
    }

    /* Style de la case non cochée */
    .auth-options .remember input[type="checkbox"]:not(:checked) {
        background: white;
        border-color: #d1cbc4;
    }

    /* Style de la case cochée */
    .auth-options .remember input[type="checkbox"]:checked {
        background: #6C3CE1;
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108, 60, 225, 0.2);
    }

    /* ✅ Symbole de la coche (checkmark) */
    .auth-options .remember input[type="checkbox"]:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: 700;
        line-height: 1;
    }

    /* Effet au survol */
    .auth-options .remember input[type="checkbox"]:hover:not(:checked) {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108, 60, 225, 0.1);
    }

    /* Style du label */
    .auth-options .remember .label-text {
        font-size: 14px;
        color: #1e1a16;
        font-weight: 500;
    }

    .auth-options .forgot-link {
        color: #6b6560;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.3s ease;
        font-weight: 500;
    }

    .auth-options .forgot-link:hover {
        color: #6C3CE1;
    }

    .btn-auth {
        width: 100%;
        padding: 16px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 30px rgba(108, 60, 225, 0.25);
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(108, 60, 225, 0.35);
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        color: #6b6560;
        font-size: 14px;
    }

    .auth-footer a {
        color: #6C3CE1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .auth-footer a:hover {
        color: #5A2FC2;
    }

    /* ===== RIGHT SIDE ===== */
    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px;
        background: linear-gradient(135deg, #f8f6f3, #f0ebe4);
        position: relative;
        overflow: hidden;
    }

    .auth-right::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(108, 60, 225, 0.04) 0%, transparent 70%);
        border-radius: 50%;
    }

    .auth-right .content {
        position: relative;
        z-index: 1;
        text-align: center;
        color: #1e1a16;
    }

    .auth-right .icon-big {
        font-size: 80px;
        margin-bottom: 20px;
        animation: float 3s ease-in-out infinite;
    }

    .auth-right h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1e1a16;
        margin-bottom: 10px;
    }

    .auth-right p {
        color: #6b6560;
        font-size: 15px;
        max-width: 300px;
        line-height: 1.7;
    }

    /* ===== ALERTES ===== */
    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger {
        background: rgba(231, 76, 60, 0.08);
        border: 1px solid rgba(231, 76, 60, 0.15);
        color: #E74C3C;
    }

    .alert-success {
        background: rgba(46, 204, 113, 0.08);
        border: 1px solid rgba(46, 204, 113, 0.15);
        color: #2ECC71;
    }

    .alert-warning {
        background: rgba(243, 156, 18, 0.08);
        border: 1px solid rgba(243, 156, 18, 0.15);
        color: #F39C12;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
            border-radius: 20px;
            min-height: auto;
        }

        .auth-left {
            padding: 30px 24px;
        }

        .auth-right {
            padding: 40px 24px;
            min-height: 200px;
        }

        .auth-right .icon-big {
            font-size: 50px;
        }

        .auth-right h2 {
            font-size: 22px;
        }

        .auth-options {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .auth-left {
            padding: 20px 16px;
        }

        .auth-title {
            font-size: 22px;
        }

        .auth-options {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .auth-options .remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            min-height: 18px;
        }
    }
</style>

<!-- ===== CONTAINER ===== -->
<div class="auth-container">

    <!-- ===== LEFT SIDE ===== -->
    <div class="auth-left">

        <a href="{{ route('welcome') }}" class="btn-back-home">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>

        <a href="{{ route('welcome') }}" class="auth-logo">
           Marché<span> digital des œuvres artistiques</span>
        </a>
        <p class="auth-subtitle">Marché Digital des Œuvres Artistiques</p>

        <h1 class="auth-title">Bienvenue</h1>
        <p class="auth-description">Connectez-vous pour accéder à votre espace</p>

        <!-- ===== ALERTES ===== -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            </div>
        @endif

        <!-- ===== FORMULAIRE ===== -->
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="exemple@email.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>
            </div>

            <div class="auth-options">
                <label class="remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="label-text">Se souvenir de moi</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-auth">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>

            <div class="auth-footer">
                Pas encore de compte ?
                <a href="{{ route('register') }}">S'inscrire</a>
            </div>

        </form>

    </div>

    <!-- ===== RIGHT SIDE ===== -->
    <div class="auth-right">
        <div class="content">
            <div class="icon-big">🎨</div>
            <h2>Marché digital des œuvres artistiques vous attend</h2>
            <p>Découvrez des milliers d'œuvres uniques créées par des artisans passionnés</p>
        </div>
    </div>

</div>

@endsection