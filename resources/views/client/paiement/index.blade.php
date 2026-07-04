@extends('layouts.client')

@section('title', 'Mes paiements - ArtMarket')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 14px 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 3px solid #6C3CE1;
    }

    .stat-card .number {
        font-size: 20px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .label {
        font-size: 11px;
        color: #95A5A6;
    }

    .stat-card .icon {
        font-size: 16px;
        display: block;
        margin-bottom: 2px;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
        align-items: center;
    }

    .filters select, .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
    }

    .filters select:focus, .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.en_attente { background: #FDE8D0; color: #F39C12; }
    .badge-status.paye { background: #D4F4E6; color: #2ECC71; }
    .badge-status.echoue { background: #FCD4D4; color: #E74C3C; }
    .badge-status.rembourse { background: #F0F0F0; color: #7F8C8D; }
    .badge-status.reussi { background: #D4F4E6; color: #2ECC71; }

    .payment-item {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border-left: 4px solid #6C3CE1;
    }

    .payment-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .payment-item .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }

    .payment-item .header .reference {
        font-weight: 600;
        color: #2D3436;
    }

    .payment-item .header .reference a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .payment-item .header .montant {
        font-weight: 700;
        color: #6C3CE1;
        font-size: 18px;
    }

    .payment-item .details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 8px;
        font-size: 13px;
        color: #95A5A6;
    }

    .payment-item .details .label {
        font-weight: 500;
        color: #2D3436;
    }

    .payment-item .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #F0F0F0;
    }

    .btn-action {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action.view { background: #E8E0F8; color: #6C3CE1; }
    .btn-action.view:hover { background: #6C3CE1; color: white; }

    .btn-export {
        background: #2D3436;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-export:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .empty-state .icon {
        font-size: 60px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #95A5A6;
        margin-bottom: 16px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
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
</style>

<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-credit-card text-primary mr-3"></i> Mes paiements
    </h1>

    <div class="stats-grid">
        <div class="stat-card" style="border-color:#6C3CE1;">
            <div class="icon">T</div>
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">Total paiements</div>
        </div>
        <div class="stat-card" style="border-color:#2ECC71;">
            <div class="icon">V</div>
            <div class="number">{{ $stats['paye'] }}</div>
            <div class="label">Payes</div>
        </div>
        <div class="stat-card" style="border-color:#2ECC71;">
            <div class="icon">$</div>
            <div class="number">{{ number_format($stats['total_montant'], 0) }} FC</div>
            <div class="label">Total paye</div>
        </div>
        <div class="stat-card" style="border-color:#F39C12;">
            <div class="icon">A</div>
            <div class="number">{{ $stats['en_attente'] }}</div>
            <div class="label">En attente</div>
        </div>
    </div>

    <div class="filters">
        <form action="{{ route('client.paiements.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
            <select name="statut">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Paye</option>
                <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Echoue</option>
                <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Rembourse</option>
            </select>

            <select name="methode">
                <option value="">Toutes les methodes</option>
                <option value="orange_money" {{ request('methode') == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                <option value="airtel_money" {{ request('methode') == 'airtel_money' ? 'selected' : '' }}>Airtel Money</option>
                <option value="mpesa" {{ request('methode') == 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                <option value="shwary" {{ request('methode') == 'shwary' ? 'selected' : '' }}>Shwary</option>
            </select>

            <input type="date" name="date_debut" value="{{ request('date_debut') }}" style="max-width:150px;">
            <span style="color:#95A5A6;">a</span>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" style="max-width:150px;">

            <button type="submit" class="btn-primary" style="padding:8px 20px;font-size:13px;">
                Filtrer
            </button>

            <a href="{{ route('client.paiements.index') }}" class="btn-secondary" style="padding:8px 20px;font-size:13px;">
                Reinitialiser
            </a>
        </form>

        <a href="{{ route('client.paiements.export', request()->all()) }}" class="btn-export">
            Exporter
        </a>
    </div>

    @forelse($paiements as $paiement)
        <div class="payment-item" style="border-left-color: 
            @switch($paiement->statut)
                @case('en_attente') #F39C12 @break
                @case('paye') #2ECC71 @break
                @case('reussi') #2ECC71 @break
                @case('echoue') #E74C3C @break
                @case('rembourse') #7F8C8D @break
                @default #6C3CE1
            @endswitch
        ">
            <div class="header">
                <div>
                    <span class="reference">
                        <a href="{{ route('client.paiements.show', $paiement->id) }}">{{ $paiement->reference }}</a>
                    </span>
                    <span class="badge-status {{ $paiement->statut }}">
                        @switch($paiement->statut)
                            @case('en_attente') En attente @break
                            @case('paye') Paye @break
                            @case('reussi') Reussi @break
                            @case('echoue') Echoue @break
                            @case('rembourse') Rembourse @break
                            @default {{ ucfirst($paiement->statut) }}
                        @endswitch
                    </span>
                </div>
                <span class="montant">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant, 2) }}</span>
            </div>

            <div class="details">
                <div>
                    <span class="label">Commande :</span>
                    #{{ $paiement->commande->reference ?? 'N/A' }}
                </div>
                <div>
                    <span class="label">Methode :</span>
                    @switch($paiement->methode)
                        @case('orange_money') Orange Money @break
                        @case('airtel_money') Airtel Money @break
                        @case('mpesa') M-Pesa @break
                        @case('carte') Carte @break
                        @case('shwary') Shwary @break
                        @default {{ $paiement->methode ?? 'N/A' }}
                    @endswitch
                </div>
                <div>
                    <span class="label">Telephone :</span>
                    {{ $paiement->phone ?? 'Non renseigne' }}
                </div>
                <div>
                    <span class="label">Date :</span>
                    {{ \Carbon\Carbon::parse($paiement->created_at)->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}
                </div>
            </div>

            <div class="footer">
                <span style="font-size:12px;color:#95A5A6;">
                    @if($paiement->transaction_id)
                        <i class="fas fa-hashtag"></i> {{ $paiement->transaction_id }}
                    @endif
                </span>
                <a href="{{ route('client.paiements.show', $paiement->id) }}" class="btn-action view">
                    <i class="fas fa-eye"></i> Voir
                </a>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="icon">P</div>
            <h3>Aucun paiement</h3>
            <p>Vous n'avez pas encore effectue de paiement.</p>
            <a href="{{ route('client.catalogue') }}" class="btn-primary">
                Explorer le catalogue
            </a>
        </div>
    @endforelse

    <div style="margin-top:20px;">
        {{ $paiements->appends(request()->query())->links() }}
    </div>
</div>

@endsection