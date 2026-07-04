@extends('layouts.artisan')

@section('title', 'Modifier la commande #' . $commande->reference)

@section('page-title', 'Modifier la commande #' . $commande->reference)

@section('content')

<style>
    .card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
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

    .form-group input,
    .form-group select,
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
    .form-group select:focus,
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 12px 28px;
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 12px 28px;
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
        padding: 12px 28px;
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

    .btn-danger:hover {
        background: #C0392B;
        transform: translateY(-2px);
        color: white;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #E8ECF1;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn-primary,
        .form-actions .btn-secondary,
        .form-actions .btn-danger {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="card">
    <form action="{{ route('artisan.commandes.update-status', $commande->id) }}" method="POST">
        @csrf
        <input type="hidden" name="statut" value="en_cours">

        <div class="form-row">
            <div class="form-group">
                <label>Reference</label>
                <input type="text" value="#{{ $commande->reference }}" disabled style="background:#F8F9FA;color:#95A5A6;">
            </div>

            <div class="form-group">
                <label>Client</label>
                <input type="text" value="{{ $commande->user->name ?? 'N/A' }}" disabled style="background:#F8F9FA;color:#95A5A6;">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Total</label>
                <input type="text" value="{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}" disabled style="background:#F8F9FA;color:#95A5A6;">
            </div>

            <div class="form-group">
                <label>Statut actuel</label>
                <select name="statut" style="width:100%;padding:10px 14px;border:2px solid #E8ECF1;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;">
                    <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="en_cours" {{ $commande->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="expediee" {{ $commande->statut == 'expediee' ? 'selected' : '' }}>Expediee</option>
                    <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livree</option>
                    <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulee</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Adresse de livraison</label>
            <textarea name="adresse_livraison" rows="2" style="width:100%;padding:10px 14px;border:2px solid #E8ECF1;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:all 0.3s ease;resize:vertical;">{{ $commande->adresse_livraison }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville_livraison" value="{{ $commande->ville_livraison }}" style="width:100%;padding:10px 14px;border:2px solid #E8ECF1;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:all 0.3s ease;">
            </div>

            <div class="form-group">
                <label>Code postal</label>
                <input type="text" name="code_postal_livraison" value="{{ $commande->code_postal_livraison }}" style="width:100%;padding:10px 14px;border:2px solid #E8ECF1;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:all 0.3s ease;">
            </div>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2" style="width:100%;padding:10px 14px;border:2px solid #E8ECF1;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:all 0.3s ease;resize:vertical;">{{ $commande->notes }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Mettre a jour
            </button>
            <a href="{{ route('artisan.commandes.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            @if(!in_array($commande->statut, ['livree', 'annulee']))
                <form action="{{ route('artisan.commandes.destroy', $commande->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette commande ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </form>
</div>

<div class="card">
    <h4 style="font-size:16px;font-weight:700;color:#2D3436;margin-bottom:16px;">Produits commandes</h4>
    <div style="display:flex;flex-direction:column;gap:8px;">
        @foreach($commande->lignes as $ligne)
            <div style="display:flex;justify-content:space-between;padding:8px 12px;background:#F8F9FA;border-radius:8px;">
                <div>
                    <strong>{{ $ligne->oeuvre->titre }}</strong>
                    <span style="color:#95A5A6;font-size:13px;margin-left:8px;">x{{ $ligne->quantite }}</span>
                </div>
                <div>
                    {{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->total, 2) }}
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection