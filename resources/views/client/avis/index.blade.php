@extends('layouts.client')

@section('title', 'Mes avis')

@section('content')

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
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

    .stat-card .icon { font-size: 20px; display:block; margin-bottom:4px; }

    .avis-item {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border-left: 4px solid #6C3CE1;
    }

    .avis-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .avis-item .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }

    .avis-item .header .oeuvre {
        font-weight: 600;
        color: #2D3436;
    }

    .avis-item .header .oeuvre a {
        color: #6C3CE1;
        text-decoration: none;
    }

    .avis-item .header .oeuvre a:hover {
        text-decoration: underline;
    }

    .avis-item .header .note {
        color: #F39C12;
        font-weight: 600;
        font-size: 16px;
    }

    .avis-item .header .date {
        font-size: 12px;
        color: #95A5A6;
    }

    .avis-item .commentaire {
        color: #2D3436;
        margin: 8px 0;
    }

    .avis-item .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid #F0F0F0;
    }

    .avis-item .footer .artisan {
        font-size: 13px;
        color: #95A5A6;
    }

    .avis-item .footer .reponse {
        font-size: 13px;
        color: #2ECC71;
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

    .btn-action {
        padding: 6px 12px;
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
    .btn-action.edit { background: #D4E8FC; color: #3498DB; }
    .btn-action.delete { background: #FCD4D4; color: #E74C3C; }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view:hover { background: #6C3CE1; color: white; }
    .btn-action.edit:hover { background: #3498DB; color: white; }
    .btn-action.delete:hover { background: #E74C3C; color: white; }

    .distribution-notes {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .distribution-notes .bar {
        flex: 1;
        height: 8px;
        background: #F0F0F0;
        border-radius: 10px;
        overflow: hidden;
        min-width: 80px;
    }

    .distribution-notes .bar .fill {
        height: 100%;
        background: #F39C12;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .distribution-notes .note-label {
        font-size: 13px;
        font-weight: 500;
        min-width: 30px;
        color: #2D3436;
    }

    .distribution-notes .count {
        font-size: 12px;
        color: #95A5A6;
        min-width: 30px;
        text-align: right;
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
</style>

<!-- ===== STATS ===== -->
<div class="stats-grid">
    <div class="stat-card" style="border-color:#6C3CE1;">
        <div class="icon">⭐</div>
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total avis</div>
    </div>
    <div class="stat-card" style="border-color:#2ECC71;">
        <div class="icon">✅</div>
        <div class="number">{{ $stats['approved'] }}</div>
        <div class="label">Approuvés</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="icon">⏳</div>
        <div class="number">{{ $stats['pending'] }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-card" style="border-color:#F39C12;">
        <div class="icon">📊</div>
        <div class="number">{{ $stats['moyenne'] }} ★</div>
        <div class="label">Note moyenne</div>
    </div>
</div>

<!-- ===== DISTRIBUTION DES NOTES ===== -->
<div class="card" style="margin-bottom:24px;">
    <h4 style="font-size:14px;font-weight:600;color:#2D3436;margin-bottom:8px;">Distribution des notes</h4>
    @foreach([5,4,3,2,1] as $note)
        <div class="distribution-notes">
            <span class="note-label">{{ $note }} ★</span>
            <div class="bar">
                @php
                    $max = max($stats['par_note']) > 0 ? max($stats['par_note']) : 1;
                    $percentage = ($stats['par_note'][$note] / $max) * 100;
                @endphp
                <div class="fill" style="width: {{ $percentage }}%;"></div>
            </div>
            <span class="count">{{ $stats['par_note'][$note] }}</span>
        </div>
    @endforeach
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('client.avis.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="🔍 Rechercher..." value="{{ request('search') }}"
               style="flex:1;min-width:150px;">

        <select name="note">
            <option value="">Toutes les notes</option>
            <option value="5" {{ request('note') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5</option>
            <option value="4" {{ request('note') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4</option>
            <option value="3" {{ request('note') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3</option>
            <option value="2" {{ request('note') == '2' ? 'selected' : '' }}>⭐⭐ 2</option>
            <option value="1" {{ request('note') == '1' ? 'selected' : '' }}>⭐ 1</option>
        </select>

        <select name="status">
            <option value="">Tous les statuts</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
        </select>

        <button type="submit" class="btn-primary" style="padding:8px 20px;font-size:13px;">
            <i class="fas fa-filter"></i> Filtrer
        </button>

        <a href="{{ route('client.avis.index') }}" class="btn-secondary" style="padding:8px 20px;font-size:13px;">
            <i class="fas fa-undo"></i> Réinitialiser
        </a>
    </form>

    <a href="{{ route('client.avis.create') }}" class="btn-primary" style="padding:8px 20px;font-size:13px;">
        <i class="fas fa-plus"></i> Donner un avis
    </a>
</div>

<!-- ===== LISTE DES AVIS ===== -->
@forelse($avis as $item)
    <div class="avis-item" style="border-left-color: {{ $item->is_approved ? '#2ECC71' : '#F39C12' }};">
        <div class="header">
            <div>
                <span class="oeuvre">
                    <a href="{{ route('client.oeuvre.show', $item->oeuvre->slug) }}" target="_blank">
                        {{ $item->oeuvre->titre }}
                    </a>
                </span>
                <span class="date" style="margin-left:12px;">
                    {{ \Carbon\Carbon::parse($item->created_at)->locale('fr')->diffForHumans() }}
                </span>
            </div>
            <div>
                <span class="note">{{ str_repeat('★', $item->note) }}{{ str_repeat('☆', 5 - $item->note) }}</span>
                <span class="badge-status {{ $item->is_approved ? 'approuve' : 'en_attente' }}" style="margin-left:8px;">
                    {{ $item->is_approved ? '✅ Approuvé' : '⏳ En attente' }}
                </span>
            </div>
        </div>

        <div class="commentaire">{{ $item->commentaire ?? 'Aucun commentaire' }}</div>

        <div class="footer">
            <span class="artisan">
                <i class="fas fa-user"></i> {{ $item->oeuvre->artisan->name ?? 'Artisan inconnu' }}
            </span>
            @if($item->reponse)
                <span class="reponse">
                    <i class="fas fa-reply"></i> {{ $item->reponse }}
                </span>
            @endif
            <div style="display:flex;gap:4px;">
                <a href="{{ route('client.avis.show', $item->id) }}" class="btn-action view">
                    <i class="fas fa-eye"></i>
                </a>
                @if(!$item->is_approved)
                    <a href="{{ route('client.avis.edit', $item->id) }}" class="btn-action edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('client.avis.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action delete" onclick="return confirm('Supprimer cet avis ?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <div class="icon">⭐</div>
        <h3>Aucun avis</h3>
        <p>Vous n'avez pas encore laissé d'avis sur les œuvres que vous avez achetées.</p>
        <a href="{{ route('client.avis.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Donner mon premier avis
        </a>
    </div>
@endforelse

<!-- ===== PAGINATION ===== -->
<div style="margin-top:20px;">
    {{ $avis->appends(request()->query())->links() }}
</div>

@endsection