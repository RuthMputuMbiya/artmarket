@extends('layouts.admin')

@section('title', 'Détails de la catégorie - ' . $categorie->nom)

@section('page-title', '📋 Détails de la catégorie')

@section('content')

<style>
    .categorie-detail {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .categorie-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #F0F0F0;
    }

    .categorie-detail .header .info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .categorie-detail .header .info .icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: #F8F9FA;
        border: 2px solid #E8ECF1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .categorie-detail .header .info .nom {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .categorie-detail .header .info .slug {
        font-size: 14px;
        color: #95A5A6;
    }

    .categorie-detail .header .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.actif { background: #D4F4E6; color: #2ECC71; }
    .badge-status.inactif { background: #FCD4D4; color: #E74C3C; }
    .badge-status.parent { background: #E8E0F8; color: #6C3CE1; }
    .badge-status.enfant { background: #D4E8FC; color: #3498DB; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        padding: 16px;
        background: #F8F9FA;
        border-radius: 12px;
    }

    .info-grid .item .label {
        font-size: 12px;
        color: #95A5A6;
        display: block;
    }

    .info-grid .item .value {
        font-size: 15px;
        font-weight: 600;
        color: #2D3436;
        margin-top: 2px;
    }

    .sous-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .sous-categorie-item {
        background: #F8F9FA;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        border: 1px solid #E8ECF1;
    }

    .sous-categorie-item:hover {
        border-color: #6C3CE1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108,60,225,0.10);
    }

    .sous-categorie-item .icon {
        font-size: 24px;
    }

    .sous-categorie-item .nom {
        font-weight: 500;
        color: #2D3436;
        font-size: 14px;
    }

    .sous-categorie-item .oeuvres {
        font-size: 12px;
        color: #95A5A6;
        margin-left: auto;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action.edit { background: #D4E8FC; color: #3498DB; }
    .btn-action.back { background: #F0F2F5; color: #2D3436; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.edit:hover { background: #3498DB; color: white; }
    .btn-action.back:hover { background: #E8ECF1; }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #95A5A6;
    }

    .empty-state .icon {
        font-size: 48px;
        display: block;
        margin-bottom: 12px;
        color: #D4D4D4;
    }

    @media (max-width: 768px) {
        .categorie-detail .header {
            flex-direction: column;
        }
        .categorie-detail .header .info .nom {
            font-size: 20px;
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
        .sous-categories-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .categorie-detail {
            padding: 16px;
        }
        .categorie-detail .header .info .icon {
            width: 48px;
            height: 48px;
            font-size: 24px;
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
        .sous-categories-grid {
            grid-template-columns: 1fr;
        }
        .categorie-detail .header .actions {
            width: 100%;
        }
        .categorie-detail .header .actions .btn-action {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="categorie-detail">

    {{-- HEADER --}}
    <div class="header">
        <div class="info">
            <div class="icon">{{ $categorie->icone ?? '' }}</div>
            <div>
                <div class="nom">{{ $categorie->nom }}</div>
                <div class="slug">
                    @if($categorie->parent)
                        <span class="badge-status enfant">Sous-catégorie</span>
                        <span style="color:#95A5A6;margin:0 8px;">→</span>
                        <span style="color:#6C3CE1;">{{ $categorie->parent->nom }}</span>
                    @else
                        <span class="badge-status parent">Catégorie principale</span>
                    @endif
                    <span class="badge-status {{ $categorie->is_active ? 'actif' : 'inactif' }}" style="margin-left:8px;">
                        {{ $categorie->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('admin.categories.edit', $categorie->id) }}" class="btn-action edit">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn-action back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    {{-- INFOS --}}
    <div class="info-grid">
        <div class="item">
            <span class="label"> Nom</span>
            <span class="value">{{ $categorie->nom }}</span>
        </div>
        <div class="item">
            <span class="label"> Description</span>
            <span class="value">{{ $categorie->description ?? 'Aucune description' }}</span>
        </div>
        <div class="item">
            <span class="label">Icône</span>
            <span class="value" style="font-size:24px;">{{ $categorie->icone ?? '' }}</span>
        </div>
        <div class="item">
            <span class="label"> Œuvres</span>
            <span class="value">{{ $categorie->oeuvres_count }} œuvres</span>
        </div>
        <div class="item">
            <span class="label"> Sous-catégories</span>
            <span class="value">{{ $categorie->enfants->count() }} sous-catégories</span>
        </div>
        <div class="item">
            <span class="label"> Date de création</span>
            <span class="value">{{ $categorie->created_at->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</span>
        </div>
        @if($categorie->parent)
        <div class="item">
            <span class="label">Catégorie parente</span>
            <span class="value" style="color:#6C3CE1;">{{ $categorie->parent->nom }}</span>
        </div>
        @endif
    </div>

    {{-- SOUS-CATÉGORIES --}}
    @if($categorie->enfants->count() > 0)
        <h3 style="font-size:16px;font-weight:700;color:#2D3436;margin-bottom:12px;">
            <i class="fas fa-folder-open"></i> Sous-catégories ({{ $categorie->enfants->count() }})
        </h3>
        <div class="sous-categories-grid">
            @foreach($categorie->enfants as $enfant)
                <div class="sous-categorie-item">
                    <span class="icon">{{ $enfant->icone ?? '' }}</span>
                    <span class="nom">{{ $enfant->nom }}</span>
                    <span class="oeuvres">{{ $enfant->oeuvres_count }} œuvres</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <span class="icon"></span>
            <p>Aucune sous-catégorie pour cette catégorie</p>
        </div>
    @endif

    {{-- ŒUVRES --}}
    @if($categorie->oeuvres->count() > 0)
        <div style="margin-top:24px;padding-top:20px;border-top:2px solid #F0F0F0;">
            <h3 style="font-size:16px;font-weight:700;color:#2D3436;margin-bottom:12px;">
                <i class="fas fa-paint-brush"></i> Dernières œuvres ({{ $categorie->oeuvres_count }})
            </h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(150px, 1fr));gap:12px;">
                @foreach($categorie->oeuvres->take(6) as $oeuvre)
                    <div style="background:#F8F9FA;border-radius:10px;padding:12px;border:1px solid #E8ECF1;text-align:center;">
                        <div style="font-size:32px;margin-bottom:4px;">🖼️</div>
                        <div style="font-weight:600;font-size:13px;color:#2D3436;">{{ $oeuvre->titre }}</div>
                        <div style="font-size:11px;color:#95A5A6;">{{ $oeuvre->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</div>
                    </div>
                @endforeach
            </div>
            @if($categorie->oeuvres_count > 6)
                <div style="text-align:center;margin-top:12px;">
                    <span style="color:#95A5A6;font-size:13px;">Et {{ $categorie->oeuvres_count - 6 }} autres œuvres...</span>
                </div>
            @endif
        </div>
    @endif

</div>

@endsection