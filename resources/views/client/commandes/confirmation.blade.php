@extends('layouts.client')

@section('title', 'Commande confirmée')

@section('content')

<style>
    .confirmation-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #D4F4E6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .confirmation-icon i {
        font-size: 40px;
        color: #2ECC71;
    }

    .confirmation-card {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        max-width: 600px;
        margin: 0 auto;
    }

    .confirmation-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .confirmation-card .reference {
        font-size: 18px;
        color: #6C3CE1;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .confirmation-card .message {
        color: #95A5A6;
        margin-bottom: 24px;
        line-height: 1.7;
    }

    .confirmation-card .details {
        background: #F8F9FA;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .confirmation-card .details .row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .confirmation-card .details .row:last-child {
        border-bottom: none;
    }

    .confirmation-card .details .row .label {
        color: #95A5A6;
    }

    .confirmation-card .details .row .value {
        font-weight: 600;
        color: #2D3436;
    }

    .confirmation-card .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
</style>

<div class="confirmation-card">
    <div class="confirmation-icon">
        <i class="fas fa-check-circle"></i>
    </div>

    <h2>Commande confirmée ! 🎉</h2>
    <div class="reference">#{{ $commande->reference }}</div>

    <p class="message">
        Votre commande a été créée avec succès.
        Vous recevrez un email de confirmation dans les prochaines minutes.
    </p>

    <div class="details">
        <div class="row">
            <span class="label">Total</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>
        <div class="row">
            <span class="label">Statut</span>
            <span class="value">
                <span class="badge-status en_attente">En attente de paiement</span>
            </span>
        </div>
        <div class="row">
            <span class="label">Date</span>
            <span class="value">{{ \Carbon\Carbon::parse($commande->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</span>
        </div>
        <div class="row">
            <span class="label">Livraison</span>
            <span class="value">{{ $commande->adresse_livraison }}</span>
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('client.paiement.checkout', $commande->id) }}" class="btn-primary">
            <i class="fas fa-credit-card"></i> Payer maintenant
        </a>
        <a href="{{ route('client.commandes.show', $commande->id) }}" class="btn-secondary">
            <i class="fas fa-eye"></i> Voir la commande
        </a>
        <a href="{{ route('client.catalogue') }}" class="btn-secondary">
            <i class="fas fa-compass"></i> Continuer mes achats
        </a>
    </div>
</div>

@endsection