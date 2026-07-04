@extends('layouts.client')

@section('title', 'Détails de l\'avis')

@section('content')

<style>
    .avis-detail {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        border-left: 5px solid #6C3CE1;
    }

    .avis-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .avis-detail .header .oeuvre {
        font-size: 18px;
        font-weight: 700;
        color: #2D3436;
    }

    .avis-detail .header .oeuvre a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .avis-detail .header .oeuvre a:hover {
        text-decoration: underline;
    }

    .avis-detail .header .note {
        font-size: 24px;
        color: #F39C12;
    }

    .avis-detail .header .date {
        font-size: 13px;
        color: #95A5A6;
    }

    .avis-detail .commentaire {
        font-size: 16px;
        color: #2D3436;
        line-height: 1.8;
        padding: 16px 0;
        border-top: 1px solid #F0F0F0;
        border-bottom: 1px solid #F0F0F0;
        margin: 12px 0;
    }

    .avis-detail .info-oeuvre {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 0;
    }

    .avis-detail .info-oeuvre img {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #E8ECF1;
    }

    .avis-detail .info-oeuvre .details .titre {
        font-weight: 600;
        color: #2D3436;
    }

    .avis-detail .info-oeuvre .details .artisan {
        font-size: 13px;
        color: #95A5A6;
    }

    .avis-detail .info-oeuvre .details .prix {
        font-weight: 600;
        color: #6C3CE1;
    }

    .reponse-artisan {
        background: #F8F9FA;
        border-radius: 12px;
        padding: 16px 20px;
        border-left: 4px solid #2ECC71;
        margin-top: 16px;
    }

    .reponse-artisan .label {
        font-size: 12px;
        font-weight: 600;
        color: #2ECC71;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reponse-artisan .contenu {
        font-size: 15px;
        color: #2D3436;
        margin-top: 4px;
        line-height: 1.7;
    }

    .reponse-artisan .date {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 4px;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.approuve { background: #D4F4E6; color: #2ECC71; }
    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
</style>

<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-star text-primary mr-3"></i> Détails de l'avis
    </h1>

    <!-- ===== AVIS ===== -->
    <div class="avis-detail">
        <div class="header">
            <div>
                <span class="oeuvre">
                    <a href="{{ route('client.oeuvre.show', $avis->oeuvre->slug) }}" target="_blank">
                        {{ $avis->oeuvre->titre }}
                    </a>
                </span>
                <span class="badge-status {{ $avis->is_approved ? 'approuve' : 'en_attente' }}" style="margin-left:12px;">
                    {{ $avis->is_approved ? '✅ Approuvé' : '⏳ En attente de validation' }}
                </span>
            </div>
            <div>
                <span class="note">{{ str_repeat('★', $avis->note) }}{{ str_repeat('☆', 5 - $avis->note) }}</span>
                <span class="date" style="margin-left:12px;">
                    {{ \Carbon\Carbon::parse($avis->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}
                </span>
            </div>
        </div>

        <div class="commentaire">
            {{ $avis->commentaire ?? 'Aucun commentaire' }}
        </div>

        <div class="info-oeuvre">
            <img src="{{ $avis->oeuvre->image_principale ?? asset('images/placeholder.jpg') }}"
                 alt="{{ $avis->oeuvre->titre }}">
            <div class="details">
                <div class="titre">{{ $avis->oeuvre->titre }}</div>
                <div class="artisan">
                    <i class="fas fa-user"></i> {{ $avis->oeuvre->artisan->name ?? 'Artisan inconnu' }}
                    <span style="margin-left:12px;">
                        <i class="fas fa-tag"></i> {{ $avis->oeuvre->categorie->nom ?? 'Non classé' }}
                    </span>
                </div>
                <div class="prix">
                    {{ $avis->oeuvre->devise->symbole ?? 'FC' }} {{ number_format($avis->oeuvre->prix_actuel, 0) }}
                </div>
            </div>
        </div>

        @if($avis->reponse)
            <div class="reponse-artisan">
                <div class="label"><i class="fas fa-reply"></i> Réponse de l'artisan</div>
                <div class="contenu">{{ $avis->reponse }}</div>
                <div class="date">
                    Répondu le {{ \Carbon\Carbon::parse($avis->repondu_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}
                </div>
            </div>
        @else
            <div style="margin-top:16px;padding:12px 16px;background:#FDE8D0;border-radius:10px;color:#F39C12;font-size:13px;">
                <i class="fas fa-clock"></i> L'artisan n'a pas encore répondu à cet avis.
            </div>
        @endif
    </div>

    <!-- ===== ACTIONS ===== -->
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('client.avis.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à mes avis
        </a>

        @if(!$avis->is_approved)
            <a href="{{ route('client.avis.edit', $avis->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="{{ route('client.avis.destroy', $avis->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet avis ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        @endif
    </div>
</div>

@endsection