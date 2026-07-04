@extends('layouts.app')

@section('title', 'Inscription - ArtMarket')

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
        padding: 40px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .auth-logo {
        font-size: 28px;
        font-weight: 800;
        color: #1e1a16;
        margin-bottom: 6px;
        text-decoration: none;
    }

    .auth-logo span {
        color: #6C3CE1;
    }

    .auth-subtitle {
        color: #6b6560;
        font-size: 13px;
        margin-bottom: 24px;
    }

    .auth-title {
        font-size: 26px;
        font-weight: 700;
        color: #1e1a16;
        margin-bottom: 4px;
    }

    .auth-title span {
        color: #6C3CE1;
    }

    .auth-description {
        color: #6b6560;
        font-size: 14px;
        margin-bottom: 24px;
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
        margin-bottom: 16px;
    }

    .auth-form label {
        display: block;
        color: #1e1a16;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .auth-form .input-group {
        position: relative;
    }

    .auth-form .input-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b6560;
        font-size: 14px;
    }

    .auth-form input {
        width: 100%;
        padding: 12px 14px 12px 44px;
        border-radius: 12px;
        border: 2px solid #e8e4df;
        background: #f8f6f3;
        color: #1e1a16;
        font-size: 13px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .btn-auth {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: #ffffff;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
        box-shadow: 0 8px 30px rgba(108, 60, 225, 0.25);
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(108, 60, 225, 0.35);
    }

    .auth-footer {
        text-align: center;
        margin-top: 18px;
        color: #6b6560;
        font-size: 13px;
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
        padding: 40px;
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
        font-size: 70px;
        margin-bottom: 16px;
        animation: float 3s ease-in-out infinite;
    }

    .auth-right h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1e1a16;
        margin-bottom: 8px;
    }

    .auth-right p {
        color: #6b6560;
        font-size: 14px;
        max-width: 280px;
        line-height: 1.7;
    }

    .auth-right .features {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        text-align: left;
    }

    .auth-right .features .feat {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #1e1a16;
    }

    .auth-right .features .feat i {
        width: 20px;
        color: #6C3CE1;
    }

    /* ===== ALERTES ===== */
    .alert {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
            border-radius: 20px;
            min-height: auto;
        }

        .auth-left {
            padding: 24px 20px;
        }

        .auth-right {
            padding: 30px 20px;
            min-height: 180px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .auth-right .icon-big {
            font-size: 40px;
        }

        .auth-right h2 {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .auth-left {
            padding: 16px 14px;
        }

        .auth-title {
            font-size: 20px;
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
            🎨 Marché<span>digital des œuvres artistiques</span>
        </a>
        <p class="auth-subtitle">Marché Digital des Œuvres</p>

        <h1 class="auth-title">Créer un compte <span></span></h1>
        <p class="auth-description">Rejoignez la communauté Marché Artistiques</p>

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

        <!-- ===== FORMULAIRE ===== -->
        <form class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="Rachel" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="rachel@email.com" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer</label>
                    <div class="input-group">
                        <i class="fas fa-check"></i>
                        <input type="password" id="password_confirmation"
                               name="password_confirmation"
                               placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone (optionnel)</label>
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="tel" id="telephone" name="telephone"
                           value="{{ old('telephone') }}"
                           placeholder="+243 971 041 435">
                </div>
            </div>


            <button type="submit" class="btn-auth">
                <i class="fas fa-user-plus"></i> S'inscrire
            </button>

            <div class="auth-footer">
                Déjà un compte ?
                <a href="{{ route('login') }}">Se connecter</a>
            </div>

        </form>

    </div>

    <!-- ===== RIGHT SIDE ===== -->
    <div class="auth-right">
        <div class="content">
            <div class="icon-big">🎨</div>
            <h2>Rejoignez l'aventure</h2>
            <p>Créez votre compte et découvrez un monde oeuvres artistiques et de créativité</p>

            <div class="features">
                <div class="feat">
                    <i class="fas fa-check-circle"></i> Accès à tout le catalogue
                </div>
                <div class="feat">
                    <i class="fas fa-check-circle"></i> Achetez en toute sécurité
                </div>
                <div class="feat">
                    <i class="fas fa-check-circle"></i> Suivez vos commandes
                </div>
            </div>
        </div>
    </div>

</div>

@endsection