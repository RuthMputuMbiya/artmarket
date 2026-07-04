@extends('layouts.admin')

@section('title', 'Détails utilisateur')

@section('page-title', '👤 Détails utilisateur')

@section('content')

<style>
    .profile-header {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #E8E0F8;
    }

    .profile-info h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .profile-info .email {
        color: #95A5A6;
        font-size: 14px;
    }

    .profile-info .role-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-top: 4px;
    }

    .profile-info .role-badge.admin { background: #E8E0F8; color: #6C3CE1; }
    .profile-info .role-badge.artisan { background: #D4F4E6; color: #2ECC71; }
    .profile-info .role-badge.client { background: #D4E8FC; color: #3498DB; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default-avatar.png') }}"
         alt="{{ $user->name }}" class="profile-avatar">

    <div class="profile-info">
        <h2>{{ $user->name }}</h2>
        <div class="email">{{ $user->email }}</div>
        <span class="role-badge {{ $user->role->slug ?? 'client' }}">
            {{ $user->role->nom ?? 'Client' }}
        </span>
        <span style="margin-left:8px;font-size:13px;color:#95A5A6;">
            Statut: <span class="badge-status {{ $user->statut }}">{{ ucfirst($user->statut) }}</span>
        </span>
        @if($user->is_verified)
            <span style="margin-left:8px;font-size:13px;color:#2ECC71;">
                <i class="fas fa-check-circle"></i> Vérifié
            </span>
        @endif
    </div>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="number">{{ $stats['total_commandes'] }}</div>
        <div class="label">Commandes</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="number">{{ $stats['total_oeuvres'] }}</div>
        <div class="label">Œuvres</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="number">{{ $stats['total_avis'] }}</div>
        <div class="label">Avis</div>
    </div>
    <div class="stat-card" style="border-color:#3498DB;">
        <div class="number">{{ number_format($stats['total_depenses'], 0) }} FC</div>
        <div class="label">Dépenses totales</div>
    </div>
</div>

<!-- ===== INFORMATIONS ===== -->
<div class="card">
    <h3 style="font-size:16px;font-weight:600;color:#2D3436;margin-bottom:16px;">📋 Informations détaillées</h3>

    <div class="info-grid">
        <div class="info-item">
            <div class="label">Téléphone</div>
            <div class="value">{{ $user->telephone ?? 'Non renseigné' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Date d'inscription</div>
            <div class="value">{{ \Carbon\Carbon::parse($user->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Dernière connexion</div>
            <div class="value">{{ $stats['derniere_connexion'] ? \Carbon\Carbon::parse($stats['derniere_connexion'])->locale('fr')->diffForHumans() : 'Jamais connecté' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Adresse</div>
            <div class="value">{{ $user->adresse ? $user->adresse . ', ' . $user->ville . ' ' . $user->code_postal . ', ' . $user->pays : 'Non renseignée' }}</div>
        </div>
        @if($user->isArtisan())
            <div class="info-item">
                <div class="label">Entreprise</div>
                <div class="value">{{ $user->entreprise ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="label">SIRET</div>
                <div class="value">{{ $user->siret ?? 'Non renseigné' }}</div>
            </div>
        @endif
        <div class="info-item" style="grid-column:1/-1;">
            <div class="label">Biographie</div>
            <div class="value">{{ $user->bio ?? 'Aucune biographie' }}</div>
        </div>
    </div>
</div>

<!-- ===== BOUTONS ===== -->
<div style="display:flex;gap:12px;margin-top:20px;">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary">
        <i class="fas fa-edit"></i> Modifier
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>

    @if($user->isArtisan() && !$user->is_verified)
        <a href="{{ route('admin.users.verify', $user->id) }}" class="btn-success" onclick="return confirm('Vérifier cet artisan ?')">
            <i class="fas fa-check"></i> Vérifier
        </a>
    @endif
</div>

@endsection