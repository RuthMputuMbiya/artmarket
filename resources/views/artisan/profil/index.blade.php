@extends('layouts.artisan')

@section('title', 'Mon profil')

@section('page-title', ' Mon profil')

@section('content')

<style>
    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 24px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #E8E0F8;
        flex-shrink: 0;
    }

    .profile-info h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 4px;
    }

    .profile-info .email {
        color: #95A5A6;
        font-size: 14px;
    }

    .profile-info .badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        background: #D4F4E6;
        color: #2ECC71;
        margin-top: 4px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #6C3CE1;
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .label {
        font-size: 12px;
        color: #95A5A6;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-item {
        background: #F8F9FA;
        padding: 12px 16px;
        border-radius: 10px;
    }

    .info-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #95A5A6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item .value {
        font-size: 14px;
        font-weight: 500;
        color: #2D3436;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== HEADER ===== -->
<div class="profile-header">
    <!-- ✅ CORRIGÉ: Utiliser photo_profil_url -->
    <img src="{{ $user->photo_profil_url ?? asset('images/default-avatar.png') }}"
         alt="{{ $user->name }}" class="profile-avatar">

    <div class="profile-info">
        <h2>{{ $user->name }}</h2>
        <div class="email">{{ $user->email }}</div>
        <span class="badge">{{ $user->role->nom ?? 'Artisan' }}</span>
        @if($user->is_verified)
            <span style="margin-left:8px;font-size:13px;color:#2ECC71;">
                <i class="fas fa-check-circle"></i> Vérifié
            </span>
        @endif
        <div style="margin-top:8px;">
            <a href="{{ route('artisan.profil.edit') }}" class="btn-primary" style="padding:8px 20px;font-size:13px;">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="number">{{ $stats['total_oeuvres'] ?? 0 }}</div>
        <div class="label">Mes œuvres</div>
    </div>
    <div class="stat-card" style="border-color:#3498DB;">
        <div class="number">{{ $stats['total_commandes'] ?? 0 }}</div>
        <div class="label">Commandes</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="number">{{ number_format($stats['total_ventes'] ?? 0, 0) }} FC</div>
        <div class="label">Chiffre d'affaires</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="number">{{ $stats['avis_moyenne'] ?? 0 }} ★</div>
        <div class="label">Note moyenne</div>
    </div>
</div>

<!-- ===== INFORMATIONS ===== -->
<div class="card">
    <h3 style="font-size:16px;font-weight:600;color:#2D3436;margin-bottom:16px;">📋 Mes informations</h3>

    <div class="info-grid">
        <div class="info-item">
            <div class="label">Nom</div>
            <div class="value">{{ $user->name }}</div>
        </div>
        <div class="info-item">
            <div class="label">Email</div>
            <div class="value">{{ $user->email }}</div>
        </div>
        <div class="info-item">
            <div class="label">Téléphone</div>
            <div class="value">{{ $user->telephone ?? 'Non renseigné' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Statut</div>
            <div class="value">
                <span class="badge-status {{ $user->statut }}">{{ ucfirst($user->statut) }}</span>
            </div>
        </div>
        <div class="info-item" style="grid-column:1/-1;">
            <div class="label">Biographie</div>
            <div class="value">{{ $user->bio ?? 'Aucune biographie' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Entreprise</div>
            <div class="value">{{ $user->entreprise ?? 'Non renseigné' }}</div>
        </div>
        <div class="info-item">
            <div class="label">SIRET</div>
            <div class="value">{{ $user->siret ?? 'Non renseigné' }}</div>
        </div>
        <div class="info-item" style="grid-column:1/-1;">
            <div class="label">Adresse</div>
            <div class="value">{{ $user->adresse ? $user->adresse . ', ' . $user->ville . ' ' . $user->code_postal . ', ' . $user->pays : 'Non renseignée' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Date d'inscription</div>
            <div class="value">{{ \Carbon\Carbon::parse($user->created_at)->locale('fr')->isoFormat('DD MMM YYYY') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Dernière connexion</div>
            <div class="value">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->locale('fr')->diffForHumans() : 'Jamais connecté' }}</div>
        </div>
    </div>
</div>

<!-- ===== ACTIONS ===== -->
<div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
    <a href="{{ route('artisan.profil.edit') }}" class="btn-primary">
        <i class="fas fa-edit"></i> Modifier mon profil
    </a>
    <button onclick="document.getElementById('deleteForm').submit()" class="btn-danger">
        <i class="fas fa-trash"></i> Supprimer mon compte
    </button>
</div>

<form id="deleteForm" action="{{ route('artisan.profil.destroy') }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection