@extends('layouts.client')

@section('title', 'Mes commandes')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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

    .stat-card .icon { font-size: 16px; display:block; margin-bottom:2px; }

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
    .badge-status.en_cours { background: #D4E8FC; color: #3498DB; }
    .badge-status.expediee { background: #D4E8FC; color: #2980B9; }
    .badge-status.livree { background: #D4F4E6; color: #2ECC71; }
    .badge-status.annulee { background: #FCD4D4; color: #E74C3C; }
    .badge-status.rembourse { background: #F0F0F0; color: #7F8C8D; }

    .commande-item {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border-left: 4px solid #6C3CE1;
    }

    .commande-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .commande-item .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }

    .commande-item .header .reference {
        font-weight: 600;
        color: #2D3436;
    }

    .commande-item .header .reference a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .commande-item .header .reference a:hover {
        text-decoration: underline;
    }

    .commande-item .header .total {
        font-weight: 700;
        color: #6C3CE1;
        font-size: 18px;
    }

    .commande-item .details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 8px;
        font-size: 13px;
        color: #95A5A6;
    }

    .commande-item .details .label {
        font-weight: 500;
        color: #2D3436;
    }

    .commande-item .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #F0F0F0;
    }

    .commande-item .footer .produits {
        font-size: 13px;
        color: #95A5A6;
        flex: 1;
        word-break: break-word;
    }

    .commande-item .footer .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
    .btn-action.pay { background: #D4F4E6; color: #2ECC71; }
    .btn-action.cancel { background: #FCD4D4; color: #E74C3C; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view:hover { background: #6C3CE1; color: white; }
    .btn-action.pay:hover { background: #2ECC71; color: white; }
    .btn-action.cancel:hover { background: #E74C3C; color: white; }

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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .stat-card {
            padding: 10px 12px;
        }

        .stat-card .number {
            font-size: 16px;
        }

        .stat-card .label {
            font-size: 10px;
        }

        .commande-item {
            padding: 12px 14px;
        }

        .commande-item .header .total {
            font-size: 16px;
        }

        .commande-item .details {
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            font-size: 12px;
        }

        .commande-item .footer .actions {
            width: 100%;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 4px 10px;
            font-size: 11px;
        }

        .filters {
            flex-direction: column;
        }

        .filters form {
            width: 100%;
        }

        .filters form input,
        .filters form select {
            width: 100%;
            min-width: auto;
        }

        .filters form .btn-primary,
        .filters form .btn-secondary {
            flex: 1;
            justify-content: center;
        }

        .btn-export {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .commande-item .details {
            grid-template-columns: 1fr;
        }

        .commande-item .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .commande-item .header .total {
            font-size: 18px;
            align-self: flex-end;
        }

        .commande-item .footer {
            flex-direction: column;
            align-items: stretch;
        }

        .commande-item .footer .produits {
            margin-bottom: 8px;
        }

        .commande-item .footer .actions {
            justify-content: center;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
            padding: 8px 12px;
            font-size: 12px;
        }

        .filters form {
            flex-direction: column;
        }

        .filters form input,
        .filters form select {
            width: 100%;
        }

        .filters form .btn-primary,
        .filters form .btn-secondary {
            width: 100%;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="icon"></div>
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total commandes</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="icon"></div>
        <div class="number">{{ $stats['en_attente'] }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-card" style="border-color:#3498DB;">
        <div class="icon"></div>
        <div class="number">{{ $stats['en_cours'] }}</div>
        <div class="label">En cours</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="icon"></div>
        <div class="number">{{ $stats['livree'] }}</div>
        <div class="label">Livrées</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="icon"></div>
        <div class="number">{{ number_format($stats['total_depenses'], 0) }} FC</div>
        <div class="label">Dépenses totales</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('client.commandes.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="🔍 Référence..." value="{{ request('search') }}"
               style="flex:1;min-width:150px;">

        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    {{ ucfirst($statut) }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_debut" value="{{ request('date_debut') }}" style="max-width:150px;">
        <span style="color:#95A5A6;">à</span>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}" style="max-width:150px;">

        <button type="submit" class="btn-primary" style="padding:8px 20px;font-size:13px;">
            <i class="fas fa-filter"></i> Filtrer
        </button>

        <a href="{{ route('client.commandes.index') }}" class="btn-secondary" style="padding:8px 20px;font-size:13px;">
            <i class="fas fa-undo"></i> Réinitialiser
        </a>
    </form>

   
</div>

<!-- ===== LISTE ===== -->
@forelse($commandes as $commande)
    <div class="commande-item" style="border-left-color: 
        @switch($commande->statut)
            @case('en_attente') #F39C12 @break
            @case('en_cours') #3498DB @break
            @case('expediee') #2980B9 @break
            @case('livree') #2ECC71 @break
            @case('annulee') #E74C3C @break
            @case('rembourse') #7F8C8D @break
            @default #6C3CE1
        @endswitch
    ">
        <div class="header">
            <div>
                <span class="reference">
                    <a href="{{ route('client.commandes.show', $commande->id) }}">#{{ $commande->reference }}</a>
                </span>
                <span class="badge-status {{ $commande->statut }}" style="margin-left:12px;">
                    {{ ucfirst($commande->statut) }}
                </span>
            </div>
            <span class="total">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>

        <div class="details">
            <div>
                <span class="label">Date :</span>
                {{ \Carbon\Carbon::parse($commande->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}
            </div>
            <div>
                <span class="label">Articles :</span>
                {{ $commande->lignes->count() }}
            </div>
            {{-- ⚠️ LIGNE LIVRAISON SUPPRIMÉE --}}
            {{--
            <div>
                <span class="label">Livraison :</span>
                {{ $commande->adresse_livraison }}
            </div>
            --}}
        </div>

        <div class="footer">
            <span class="produits">
                <i class="fas fa-box"></i>
                {{ $commande->lignes->pluck('oeuvre.titre')->implode(', ') }}
            </span>

            <div class="actions">
                <a href="{{ route('client.commandes.show', $commande->id) }}" class="btn-action view">
                    <i class="fas fa-eye"></i> Voir
                </a>

                @if($commande->statut === 'en_attente')
                    <a href="{{ route('client.paiement.checkout', $commande->id) }}" class="btn-action pay">
                        <i class="fas fa-credit-card"></i> Payer
                    </a>
                    <form action="{{ route('client.commande.annuler', $commande->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-action cancel" onclick="return confirm('Annuler cette commande ?')">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div class="icon">📦</div>
        <h3>Aucune commande</h3>
        <p>Vous n'avez pas encore passé de commande.</p>
        <a href="{{ route('client.catalogue') }}" class="btn-primary">
            <i class="fas fa-compass"></i> Explorer le catalogue
        </a>
    </div>
@endforelse

<!-- ===== PAGINATION ===== -->
<div style="margin-top:20px;">
    {{ $commandes->appends(request()->query())->links() }}
</div>

@endsection