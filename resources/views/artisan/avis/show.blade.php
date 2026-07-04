@extends('layouts.artisan')

@section('title', 'Details de l\'avis')

@section('page-title', 'Details de l\'avis')

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

    .avis-detail .header .client {
        font-size: 18px;
        font-weight: 700;
        color: #2D3436;
    }

    .avis-detail .header .note {
        font-size: 24px;
        color: #F39C12;
        letter-spacing: 2px;
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

    .reponse-form {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .reponse-form .form-group {
        margin-bottom: 16px;
    }

    .reponse-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 6px;
    }

    .reponse-form textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
        outline: none;
        min-height: 120px;
        resize: vertical;
    }

    .reponse-form textarea:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .reponse-form .form-error {
        color: #E74C3C;
        font-size: 12px;
        margin-top: 4px;
    }

    .reponse-existante {
        background: #F8F9FA;
        border-radius: 12px;
        padding: 16px 20px;
        border-left: 4px solid #2ECC71;
        margin-top: 16px;
    }

    .reponse-existante .label {
        font-size: 12px;
        font-weight: 600;
        color: #2ECC71;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reponse-existante .contenu {
        font-size: 15px;
        color: #2D3436;
        margin-top: 4px;
        line-height: 1.7;
    }

    .reponse-existante .date {
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

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 10px 24px;
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
        padding: 10px 24px;
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

    @media (max-width: 768px) {
        .avis-detail .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .avis-detail .header .note {
            font-size: 20px;
        }

        .avis-detail .info-oeuvre {
            flex-direction: column;
            align-items: flex-start;
        }

        .avis-detail .info-oeuvre img {
            width: 100%;
            height: 200px;
        }

        .reponse-form {
            padding: 16px;
        }

        .reponse-form .form-actions {
            flex-direction: column;
        }

        .reponse-form .form-actions .btn-primary,
        .reponse-form .form-actions .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .avis-detail {
            padding: 16px;
        }

        .avis-detail .header .client {
            font-size: 16px;
        }

        .avis-detail .commentaire {
            font-size: 14px;
        }

        .avis-detail .info-oeuvre .details .titre {
            font-size: 14px;
        }
    }
</style>

<!-- ===== AVIS ===== -->
<div class="avis-detail">
    <div class="header">
        <div>
            <span class="client">{{ $avis->user->name ?? 'Client' }}</span>
            <span class="badge-status {{ $avis->is_approved ? 'approuve' : 'en_attente' }}" style="margin-left:12px;">
                {{ $avis->is_approved ? 'Approuve' : 'En attente' }}
            </span>
        </div>
        <div>
            <span class="note">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $avis->note)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </span>
            <span class="date" style="margin-left:12px;">
                {{ $avis->created_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}
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
                Artisan: {{ $avis->oeuvre->artisan->name ?? 'Artisan inconnu' }}
                <span style="margin-left:12px;">
                    Categorie: {{ $avis->oeuvre->categorie->nom ?? 'Non classe' }}
                </span>
            </div>
            <div class="prix">
                {{ $avis->oeuvre->devise->symbole ?? 'FC' }} {{ number_format($avis->oeuvre->prix_actuel, 0) }}
            </div>
        </div>
    </div>

    @if($avis->commande)
        <div style="margin-top:8px;font-size:13px;color:#95A5A6;">
            Commande: #{{ $avis->commande->reference }}
        </div>
    @endif
</div>

<!-- ===== REPONSE EXISTANTE ===== -->
@if($avis->reponse)
    <div class="reponse-existante">
        <div class="label">Votre reponse</div>
        <div class="contenu">{{ $avis->reponse }}</div>
        <div class="date">
            Repondu le {{ $avis->repondu_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}
        </div>
    </div>

    <div style="margin-top:16px;display:flex;gap:12px;">
        <a href="{{ route('artisan.avis.index') }}" class="btn-secondary">
            Retour
        </a>
    </div>
@else
    <!-- ===== FORMULAIRE DE REPONSE ===== -->
    <div class="reponse-form" id="repondre">
        <h3 style="font-size:18px;font-weight:700;color:#2D3436;margin-bottom:16px;">
            Repondre a cet avis
        </h3>

        <form action="{{ route('artisan.avis.respond', $avis->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="reponse">Votre message</label>
                <textarea id="reponse" name="reponse" class="form-control @error('reponse') is-invalid @enderror"
                          placeholder="Merci pour votre avis...">{{ old('reponse') }}</textarea>
                @error('reponse')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <button type="submit" class="btn-primary">
                    Envoyer la reponse
                </button>
                <a href="{{ route('artisan.avis.index') }}" class="btn-secondary">
                    Retour
                </a>
            </div>
        </form>
    </div>
@endif

@endsection