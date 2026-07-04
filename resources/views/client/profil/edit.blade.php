@extends('layouts.client')

@section('title', 'Modifier mon profil')

@section('content')

<style>
    .profile-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .profile-card .avatar-section {
        text-align: center;
        margin-bottom: 24px;
    }

    .profile-card .avatar-section .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #6C3CE1;
        margin: 0 auto 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-card .avatar-section .avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(108,60,225,0.3);
    }

    .profile-card .avatar-section .avatar-label {
        font-size: 14px;
        color: #95A5A6;
        cursor: pointer;
    }

    .profile-card .avatar-section .avatar-label i {
        color: #6C3CE1;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-group label .required {
        color: #E74C3C;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #E8ECF1;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 4px rgba(108,60,225,0.10);
    }

    .form-group .help-text {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 4px;
        display: block;
    }

    .form-group .error {
        color: #E74C3C;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #E8ECF1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
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
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #F0F0F0;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: rgba(46, 204, 113, 0.08);
        border: 1px solid rgba(46, 204, 113, 0.15);
        color: #2ECC71;
    }

    .alert-danger {
        background: rgba(231, 76, 60, 0.08);
        border: 1px solid rgba(231, 76, 60, 0.15);
        color: #E74C3C;
    }

    .input-file-hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn-primary,
        .form-actions .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .profile-card {
            padding: 20px;
        }

        .profile-card .avatar-section .avatar {
            width: 100px;
            height: 100px;
        }
    }

    @media (max-width: 480px) {
        .profile-container {
            padding: 0 10px;
        }
        .profile-card {
            padding: 16px;
        }
        .profile-card .avatar-section .avatar {
            width: 80px;
            height: 80px;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('client.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ===== AVATAR ===== -->
            <div class="avatar-section">
                @php
                    $avatarUrl = auth()->user()->photo_profil 
                        ? asset('storage/' . auth()->user()->photo_profil) 
                        : asset('images/default-avatar.png');
                @endphp
                <img src="{{ $avatarUrl }}" alt="Avatar" class="avatar" id="avatarPreview">
                <div class="avatar-label" onclick="document.getElementById('avatarInput').click();">
                    <i class="fas fa-camera"></i>
                    <span>Cliquez pour changer la photo</span>
                </div>
                <input type="file" name="photo_profil" id="avatarInput" class="input-file-hidden" accept="image/*">
                @error('photo_profil')
                    <span class="error">{{ $message }}</span>
                @enderror
                <span class="help-text">Formats: JPG, PNG, GIF (max 2MB)</span>
            </div>

            <!-- ===== INFORMATIONS PERSONNELLES ===== -->
            <div class="section-title">Informations personnelles</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nom complet <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Telephone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', auth()->user()->telephone ?? '') }}" placeholder="+243 999 999 999">
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', auth()->user()->ville ?? '') }}" placeholder="Kinshasa, Lubumbashi...">
                    @error('ville')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Adresse</label>
                <textarea name="adresse" rows="2" placeholder="Votre adresse complete">{{ old('adresse', auth()->user()->adresse ?? '') }}</textarea>
                @error('adresse')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- ===== CHANGER LE MOT DE PASSE ===== -->
            <div class="section-title" style="margin-top:24px;">Changer le mot de passe</div>
            <p style="font-size:13px;color:#95A5A6;margin-bottom:16px;">Laissez vide si vous ne souhaitez pas changer votre mot de passe</p>

            <div class="form-row">
                <div class="form-group">
                    <label>Mot de passe actuel</label>
                    <input type="password" name="current_password" placeholder="••••••••">
                    @error('current_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_password" placeholder="••••••••">
                    @error('new_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" name="new_password_confirmation" placeholder="••••••••">
            </div>

            <!-- ===== BOUTONS ===== -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="{{ route('client.profil.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Annuler
                </a>
            </div>

        </form>

        <!-- ===== SUPPRIMER LE COMPTE ===== -->
        <div style="margin-top:30px;padding-top:20px;border-top:2px solid #FCD4D4;">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                <div>
                    <h4 style="font-size:14px;font-weight:700;color:#E74C3C;">Zone dangereuse</h4>
                    <p style="font-size:13px;color:#95A5A6;">Supprimer definitivement votre compte et toutes vos donnees</p>
                </div>
                <form action="{{ route('client.profil.destroy') }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ? Cette action est irreversible.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash"></i> Supprimer mon compte
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== PREVISUALISATION DE L'AVATAR =====
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');

        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        avatarPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>

@endsection