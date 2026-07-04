@extends('layouts.admin')

@section('title', 'Détails de la catégorie')

@section('page-title', '🏷️ Détails de la catégorie')

@section('content')

<style>
    .header-category {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .category-icon-big {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        background: #E8E0F8;
        border: 3px solid #6C3CE1;
    }

    .header-category .info h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 4px;
    }

    .header-category .info .slug {
        color: #95A5A6;
        font-size: 14px;
    }

    .header-category .info .desc {
        margin-top: 8px;
        color: #2D3436;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
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

    .oeuvre-mini {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .oeuvre-mini:last-child {
        border-bottom: none;
    }

    .oeuvre-mini img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #E8ECF1;
    }

    .oeuvre-mini .info {
        flex: 1;
    }

    .oeuvre-mini .info .titre {
        font-weight: 500;
        color: #2D3436;
    }

    .oeuvre-mini .info .artisan {
        font-size: 12px;
        color: #95A5A6;
    }

    .oeuvre-mini .prix {
        font-weight: 600;
        color: #6C3CE1;
    }

    @media (max-width: 768px) {
        .header-category {
            flex-direction: column;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== HEADER ===== -->
<div class="header-category">
    <div class="category-icon-big">
        {{ $categorie->icone ?? '📁' }}
    </div>

    <div class="info">
        <h2>{{ $categorie->nom }}</h2>
        <div class="slug">Slug: {{ $categorie->slug }}</div>
        <div class="desc">{{ $categorie->description ?? 'Aucune description' }}</div>
        <div style="margin-top:8px;">
            <span class="badge-status {{ $categorie->is_active ? 'actif' : 'inactif' }}">
                {{ $categorie->is_active ? '✅ Actif' : '❌ Inactif' }}
            </span>
            <span style="margin-left:12px;font-size:13px;color:#95A5A6;">
                Créée le {{ \Carbon\Carbon::parse($categorie->created_at)->locale('fr')->isoFormat('DD MMM YYYY') }}
            </span>
        </div>
    </div>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="number">{{ $categorie->oeuvres_count }}</div>
        <div class="label">Total œuvres</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="number">{{ $categorie->oeuvres->where('statut', 'publiee')->count() }}</div>
        <div class="label">Publiées</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="number">{{ $categorie->oeuvres->where('statut', 'en_attente')->count() }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-card" style="border-color:#7F8C8D;">
        <div class="number">{{ $categorie->oeuvres->where('statut', 'brouillon')->count() }}</div>
        <div class="label">Brouillons</div>
    </div>
</div>

<!-- ===== INFORMATIONS ===== -->
<div class="card" style="margin-bottom:24px;">
    <h3 style="font-size:16px;font-weight:600;color:#2D3436;margin-bottom:16px;">📋 Informations détaillées</h3>

    <div class="info-grid">
        <div class="info-item">
            <div class="label">Nom</div>
            <div class="value">{{ $categorie->nom }}</div>
        </div>
        <div class="info-item">
            <div class="label">Slug</div>
            <div class="value">{{ $categorie->slug }}</div>
        </div>
        <div class="info-item">
            <div class="label">Icône</div>
            <div class="value" style="font-size:30px;">{{ $categorie->icone ?? '📁' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Statut</div>
            <div class="value">
                <span class="badge-status {{ $categorie->is_active ? 'actif' : 'inactif' }}">
                    {{ $categorie->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
        </div>
        <div class="info-item" style="grid-column:1/-1;">
            <div class="label">Description</div>
            <div class="value">{{ $categorie->description ?? 'Aucune description' }}</div>
        </div>
    </div>
</div>

<!-- ===== DERNIÈRES ŒUVRES ===== -->
<div class="card">
    <h3 style="font-size:16px;font-weight:600;color:#2D3436;margin-bottom:16px;">
        🖼️ Dernières œuvres dans cette catégorie
        <span style="font-size:13px;font-weight:400;color:#95A5A6;">
            ({{ $categorie->oeuvres_count }} au total)
        </span>
    </h3>

    @forelse($categorie->oeuvres as $oeuvre)
        <div class="oeuvre-mini">
            <img src="{{ $oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                 alt="{{ $oeuvre->titre }}">
            <div class="info">
                <div class="titre">{{ $oeuvre->titre }}</div>
                <div class="artisan">
                    <i class="fas fa-user"></i> {{ $oeuvre->artisan->name ?? 'Artisan inconnu' }}
                    <span style="margin-left:8px;">
                        <span class="badge-status {{ $oeuvre->statut }}">{{ ucfirst($oeuvre->statut) }}</span>
                    </span>
                </div>
            </div>
            <div class="prix">
                {{ $oeuvre->devise->symbole ?? 'FC' }} {{ number_format($oeuvre->prix_actuel, 0) }}
            </div>
        </div>
    @empty
        <p style="color:#95A5A6;text-align:center;">Aucune œuvre dans cette catégorie</p>
    @endforelse

    @if($categorie->oeuvres_count > 10)
        <div style="text-align:center;margin-top:12px;">
            <a href="{{ route('admin.oeuvres.index', ['categorie' => $categorie->id]) }}" class="btn-secondary" style="padding:8px 24px;font-size:13px;">
                <i class="fas fa-eye"></i> Voir toutes les œuvres
            </a>
        </div>
    @endif
</div>

<!-- ===== BOUTONS ===== -->
<div style="display:flex;gap:12px;margin-top:20px;">
    <a href="{{ route('admin.categories.edit', $categorie->id) }}" class="btn-primary">
        <i class="fas fa-edit"></i> Modifier
    </a>
    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>

    @if($categorie->oeuvres_count == 0)
        <form action="{{ route('admin.categories.destroy', $categorie->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cette catégorie ?')">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </form>
    @endif
</div>

@endsection