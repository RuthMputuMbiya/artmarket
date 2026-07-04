@extends('layouts.client')

@section('title', 'Mon profil')

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

    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .info-row .label {
        font-weight: 600;
        color: #2D3436;
        width: 150px;
    }

    .info-row .value {
        color: #95A5A6;
        flex: 1;
    }

    .info-row .value a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .info-row .value a:hover {
        text-decoration: underline;
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

    .btn-danger {
        background: #E74C3C;
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

    .btn-danger:hover {
        background: #C0392B;
        transform: translateY(-2px);
        color: white;
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

    .actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #E8ECF1;
        flex-wrap: wrap;
    }

    .input-file-hidden {
        display: none;
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            gap: 4px;
        }
        .info-row .label {
            width: 100%;
        }
        .profile-card {
            padding: 20px;
        }
        .profile-card .avatar-section .avatar {
            width: 100px;
            height: 100px;
        }
        .actions {
            flex-direction: column;
        }
        .actions .btn-primary,
        .actions .btn-secondary,
        .actions .btn-danger {
            width: 100%;
            justify-content: center;
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
            <span style="font-size:12px;color:#95A5A6;display:block;margin-top:4px;">Formats: JPG, PNG, GIF (max 2MB)</span>
        </div>

        <!-- ===== INFORMATIONS ===== -->
        <div class="info-row">
            <span class="label">Nom complet</span>
            <span class="value">{{ auth()->user()->name }}</span>
        </div>

        <div class="info-row">
            <span class="label">Email</span>
            <span class="value">{{ auth()->user()->email }}</span>
        </div>

        <div class="info-row">
            <span class="label">Telephone</span>
            <span class="value">{{ auth()->user()->telephone ?? 'Non renseigne' }}</span>
        </div>

        <div class="info-row">
            <span class="label">Ville</span>
            <span class="value">{{ auth()->user()->ville ?? 'Non renseigne' }}</span>
        </div>

        <div class="info-row">
            <span class="label">Adresse</span>
            <span class="value">{{ auth()->user()->adresse ?? 'Non renseigne' }}</span>
        </div>

        <div class="info-row">
            <span class="label">Role</span>
            <span class="value">
                @if(auth()->user()->isAdmin())
                    Administrateur
                @elseif(auth()->user()->isArtisan())
                    Artisan
                @else
                    Client
                @endif
            </span>
        </div>

        <!-- ===== ACTIONS ===== -->
        <div class="actions">
            <a href="{{ route('client.profil.edit') }}" class="btn-primary">
                <i class="fas fa-edit"></i> Modifier le profil
            </a>
            <a href="{{ route('client.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

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