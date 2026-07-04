@extends('layouts.artisan')

@section('title', 'Statistiques')

@section('page-title', 'Statistiques')

@section('content')

<style>
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
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

    .stat-card .icon { font-size: 18px; display:block; margin-bottom:4px; }

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
        height: 160px;
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
        background: linear-gradient(180deg, #6C3CE1, #8B6CE6);
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

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .top-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .top-item:last-child {
        border-bottom: none;
    }

    .top-item .name {
        font-weight: 500;
        color: #2D3436;
    }

    .top-item .value {
        font-weight: 600;
        color: #6C3CE1;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .stat-card {
            padding: 12px 14px;
        }

        .stat-card .number {
            font-size: 18px;
        }
    }
</style>

<!-- ===== STATS GLOBALES ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="icon"></div>
        <div class="number">{{ $stats['total_oeuvres'] }}</div>
        <div class="label">Total œuvres</div>
        <div style="font-size:10px;color:#95A5A6;margin-top:2px;">
            {{ $stats['oeuvres_publiees'] }} publiées
        </div>
    </div>
    <div class="stat-card" style="border-color:#3498DB;">
        <div class="icon"></div>
        <div class="number">{{ $stats['total_commandes'] }}</div>
        <div class="label">Commandes</div>
        <div style="font-size:10px;color:#95A5A6;margin-top:2px;">
            {{ $stats['commandes_livrees'] }} livrées
        </div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="icon"></div>
        <div class="number">{{ number_format($stats['total_ventes'], 0) }} FC</div>
        <div class="label">Chiffre d'affaires</div>
        <div style="font-size:10px;color:#95A5A6;margin-top:2px;">
            Commission: {{ number_format($stats['commission_totale'], 0) }} FC
        </div>
    </div>
   
    <div class="stat-card" style="border-color:#E74C3C;">
        <div class="icon"></div>
        <div class="number">{{ $stats['stock_total'] }}</div>
        <div class="label">Stock total</div>
        <div style="font-size:10px;color:#95A5A6;margin-top:2px;">
            ⚠️ {{ $stats['stock_alerte'] }} en alerte
        </div>
    </div>
    <div class="stat-card" style="border-color:#FF6B6B;">
        <div class="icon"></div>
        <div class="number">{{ $stats['commandes_en_attente'] }}</div>
        <div class="label">En attente</div>
        <div style="font-size:10px;color:#95A5A6;margin-top:2px;">
            {{ $stats['commandes_en_cours'] }} en cours
        </div>
    </div>
</div>

<!-- ===== GRAPHIQUE VENTES MENSUELLES ===== -->
<div class="chart-container">
    <h3> Ventes mensuelles</h3>
    <div class="bar-chart">
        @foreach($ventes_par_mois as $vente)
            <div class="bar-item">
                <div class="bar-value">{{ number_format($vente['total'], 0) }}</div>
                <div class="bar" style="height: {{ $vente['total'] > 0 ? ($vente['total'] / max(array_column($ventes_par_mois, 'total') ?: [1]) * 140) : 4 }}px;"></div>
                <div class="bar-label">{{ $vente['mois'] }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- ===== GRAPHIQUE COMMANDES 7 JOURS ===== -->
<div class="chart-container">
    <h3>Commandes des 7 derniers jours</h3>
    <div class="bar-chart">
        @foreach($commandes_7_jours as $cmd)
            <div class="bar-item">
                <div class="bar-value">{{ $cmd['total'] }}</div>
                <div class="bar" style="height: {{ $cmd['total'] > 0 ? ($cmd['total'] / max(array_column($commandes_7_jours, 'total') ?: [1]) * 140) : 4 }}px; background: linear-gradient(180deg, #2ECC71, #82E0AA);"></div>
                <div class="bar-label">{{ $cmd['date'] }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- ===== TOP CLIENTS & TOP ŒUVRES ===== -->
<div class="grid-2">
    <div class="chart-container">
        <h3> Top 5 clients</h3>
        @forelse($top_clients as $client)
            <div class="top-item">
                <span class="name">{{ $client->user->name ?? 'Client inconnu' }}</span>
                <span class="value">{{ number_format($client->total_depenses, 0) }} FC</span>
            </div>
        @empty
            <p style="color:#95A5A6;text-align:center;">Aucun client</p>
        @endforelse
    </div>

    <div class="chart-container">
        <h3> Top 5 œuvres</h3>
        @forelse($top_oeuvres as $oeuvre)
            <div class="top-item">
                <span class="name">{{ $oeuvre->titre }}</span>
                <span class="value">{{ $oeuvre->total_ventes ?? 0 }} ventes</span>
            </div>
        @empty
            <p style="color:#95A5A6;text-align:center;">Aucune œuvre vendue</p>
        @endforelse
    </div>
</div>

<!-- ===== VENTES PAR CATÉGORIE ===== -->
<div class="chart-container">
    <h3> Ventes par catégorie</h3>
    @forelse($ventes_par_categorie as $categorie)
        <div class="top-item">
            <span class="name">{{ $categorie->categorie->nom ?? 'Non classé' }}</span>
            <span class="value">{{ $categorie->total_ventes ?? 0 }} ventes</span>
        </div>
    @empty
        <p style="color:#95A5A6;text-align:center;">Aucune vente par catégorie</p>
    @endforelse
</div>

@endsection