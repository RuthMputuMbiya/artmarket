@extends('layouts.artisan')

@section('title', 'Mon Atelier - Artisan')

@section('page-title', 'Mon Atelier')

@section('content')

<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border-left: 4px solid var(--artisan-primary);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .stat-card .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .stat-card .stat-icon.green { background: #D4F4E6; color: #2ECC71; }
    .stat-card .stat-icon.orange { background: #FDE8D0; color: #F39C12; }
    .stat-card .stat-icon.blue { background: #D4E8FC; color: #3498DB; }
    .stat-card .stat-icon.purple { background: #E8E0F8; color: #6C3CE1; }
    .stat-card .stat-icon.pink { background: #FCE4EC; color: #E91E63; }
    .stat-card .stat-icon.teal { background: #E0F2F1; color: #00897B; }

    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-card .stat-label {
        font-size: 13px;
        color: #95A5A6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .chart-container h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 16px;
    }

    .bar-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 180px;
        gap: 8px;
        padding-top: 16px;
    }

    .bar-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .bar {
        width: 100%;
        max-width: 40px;
        background: linear-gradient(180deg, #2ECC71, #82E0AA);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
        transition: height 0.6s ease;
    }

    .bar-label {
        font-size: 11px;
        color: #95A5A6;
        margin-top: 6px;
    }

    .bar-value {
        font-size: 10px;
        font-weight: 600;
        color: #2D3436;
        margin-bottom: 4px;
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
    .badge-status.expediee { background: #D4F4E6; color: #2ECC71; }
    .badge-status.livree { background: #D4F4E6; color: #27AE60; }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .table-container {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-custom thead {
        background: #F8F9FA;
    }

    .table-custom th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #2D3436;
        border-bottom: 2px solid #E8ECF1;
    }

    .table-custom td {
        padding: 12px 16px;
        border-bottom: 1px solid #E8ECF1;
        color: #2D3436;
    }

    .table-custom tbody tr:hover {
        background: rgba(46, 204, 113, 0.03);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .quick-action {
        background: white;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        text-decoration: none;
        color: #2D3436;
        border: 2px solid transparent;
    }

    .quick-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        border-color: #2ECC71;
    }

    .quick-action .icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .quick-action .label {
        font-weight: 600;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-card .stat-number {
            font-size: 20px;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== ACTIONS RAPIDES ===== -->
<div class="quick-actions">
    <a href="{{ route('artisan.oeuvres.create') }}" class="quick-action">
        <div class="icon"></div>
        <div class="label">Ajouter une œuvre</div>
    </a>
    <a href="{{ route('artisan.commandes.index') }}" class="quick-action">
        <div class="icon"></div>
        <div class="label">Voir les commandes</div>
    </a>
    <a href="{{ route('artisan.statistiques') }}" class="quick-action">
        <div class="icon"></div>
        <div class="label">Statistiques</div>
    </a>
    <a href="{{ route('artisan.profil.edit') }}" class="quick-action">
        <div class="icon"></div>
        <div class="label">Mon profil</div>
    </a>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-palette"></i></div>
        <div class="stat-number">{{ $stats['total_oeuvres'] ?? 0 }}</div>
        <div class="stat-label">Mes œuvres</div>
        <div style="font-size:11px;color:#95A5A6;margin-top:4px;">
            {{ $stats['oeuvres_publiees'] ?? 0 }} publiées · {{ $stats['oeuvres_brouillon'] ?? 0 }} brouillons
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-number">{{ $stats['total_commandes'] ?? 0 }}</div>
        <div class="stat-label">Commandes</div>
        <div style="font-size:11px;color:#95A5A6;margin-top:4px;">
            {{ $stats['commandes_en_attente'] ?? 0 }} en attente
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-credit-card"></i></div>
        <div class="stat-number">{{ number_format($stats['total_ventes'] ?? 0, 0) }} FC</div>
        <div class="stat-label">Chiffre d'affaires</div>
        <div style="font-size:11px;color:#95A5A6;margin-top:4px;">
            {{ $stats['commandes_livrees'] ?? 0 }} commandes livrées
        </div>
    </div>
  
</div>

<!-- ===== GRAPHIQUE VENTES ===== -->
<div class="chart-container">
    <h3>Ventes des 7 derniers jours</h3>
    <div class="bar-chart">
        @foreach($ventes_7_jours ?? [] as $vente)
            <div class="bar-item">
                <div class="bar-value">{{ number_format($vente['total'] ?? 0, 0) }}</div>
                <div class="bar" style="height: {{ ($vente['total'] ?? 0) > 0 ? (($vente['total'] ?? 0) / max(array_column($ventes_7_jours ?? [], 'total') ?: [1]) * 160) : 4 }}px;"></div>
                <div class="bar-label">{{ $vente['date'] ?? '' }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- ===== TOP ŒUVRES & DERNIÈRES COMMANDES ===== -->
<div class="grid-2">
    <div class="chart-container">
        <h3> Top 5 œuvres</h3>
        @forelse($top_oeuvres ?? [] as $oeuvre)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #F0F0F0;">
                <span style="font-weight:500;">{{ $oeuvre->titre ?? '' }}</span>
                <span style="font-weight:600;color:#2ECC71;">{{ $oeuvre->total_ventes ?? 0 }} ventes</span>
            </div>
        @empty
            <p style="color:#95A5A6;text-align:center;">Aucune vente</p>
        @endforelse
    </div>

    <div class="chart-container">
        <h3>Dernières commandes</h3>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Réf.</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieres_commandes ?? [] as $commande)
                        <tr>
                            <td><strong>#{{ $commande->reference ?? '' }}</strong></td>
                            <td>{{ $commande->user->name ?? 'N/A' }}</td>
                            <td>{{ number_format($commande->total_ttc ?? 0, 0) }} FC</td>
                            <td><span class="badge-status {{ $commande->statut ?? '' }}">{{ ucfirst($commande->statut ?? '') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#95A5A6;">Aucune commande</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== DERNIERS AVIS ===== -->
<div class="chart-container">
    <h3>Derniers avis reçus</h3>
    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Œuvre</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($derniers_avis ?? [] as $avis)
                    <tr>
                        <td><strong>{{ $avis->user->name ?? 'N/A' }}</strong></td>
                        <td>{{ $avis->oeuvre->titre ?? 'N/A' }}</td>
                        <td><span style="color:#F39C12;">{{ str_repeat('★', $avis->note ?? 0) }}{{ str_repeat('☆', 5 - ($avis->note ?? 0)) }}</span></td>
                        <td>{{ Str::limit($avis->commentaire ?? '', 50) }}</td>
                        <td>{{ \Carbon\Carbon::parse($avis->created_at ?? now())->locale('fr')->isoFormat('DD MMM YYYY') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:#95A5A6;">Aucun avis</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection