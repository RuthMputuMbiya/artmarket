@extends('layouts.artisan')

@section('title', 'Mes commandes')

@section('page-title', 'Commandes')

@section('content')

<style>
    .stats-mini {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-mini {
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border-left: 4px solid #6C3CE1;
        transition: all 0.3s ease;
    }

    .stat-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .stat-mini .number {
        font-size: 24px;
        font-weight: 700;
        color: #2D3436;
    }

    .stat-mini .label {
        font-size: 12px;
        color: #95A5A6;
        margin-top: 2px;
    }

    .stat-mini .icon {
        font-size: 24px;
        display: block;
        margin-bottom: 4px;
    }

    .stat-mini.total { border-left-color: #6C3CE1; }
    .stat-mini.attente { border-left-color: #F39C12; }
    .stat-mini.cours { border-left-color: #3498DB; }
    .stat-mini.livree { border-left-color: #2ECC71; }
    .stat-mini.annulee { border-left-color: #E74C3C; }
    .stat-mini.ventes { border-left-color: #9B59B6; }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        align-items: center;
        background: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .filters select,
    .filters input {
        padding: 8px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
        background: white;
        color: #2D3436;
    }

    .filters select:focus,
    .filters input:focus {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 3px rgba(108,60,225,0.1);
    }

    .filters .btn-primary {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .filters .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
        color: white;
    }

    .filters .btn-secondary {
        background: #F0F2F5;
        color: #2D3436;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filters .btn-secondary:hover {
        background: #E8ECF1;
        transform: translateY(-2px);
    }

    .filters .btn-export {
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

    .filters .btn-export:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
        color: white;
    }

    .table-container {
        overflow-x: auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 700px;
    }

    .table-custom thead {
        background: #F8F9FA;
    }

    .table-custom th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #2D3436;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E8ECF1;
    }

    .table-custom td {
        padding: 14px 16px;
        border-bottom: 1px solid #F0F0F0;
        vertical-align: middle;
    }

    .table-custom tr:hover {
        background: #F8F9FA;
    }

    .table-custom .reference {
        font-weight: 600;
        color: #6C3CE1;
        text-decoration: none;
    }

    .table-custom .reference:hover {
        text-decoration: underline;
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

    /* ===== ACTIONS (TOUT SUR UNE LIGNE) ===== */
    .actions {
        display: flex;
        gap: 4px;
        align-items: center;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: inherit;
        font-weight: 500;
        flex-shrink: 0;
    }

    .action-btn i {
        font-size: 14px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .action-btn.view { background: #E8E0F8; color: #6C3CE1; }
    .action-btn.view:hover { background: #6C3CE1; color: white; }

    .action-btn.edit { background: #D4E8FC; color: #3498DB; }
    .action-btn.edit:hover { background: #3498DB; color: white; }

    .action-btn.delete { background: #FCD4D4; color: #E74C3C; }
    .action-btn.delete:hover { background: #E74C3C; color: white; }

    .action-btn.status { background: #FDE8D0; color: #F39C12; }
    .action-btn.status:hover { background: #F39C12; color: white; }

    /* ===== TOOLTIP ===== */
    .action-btn {
        position: relative;
    }

    .action-btn:hover::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        font-size: 10px;
        padding: 3px 10px;
        border-radius: 4px;
        white-space: nowrap;
        font-weight: 500;
        z-index: 10;
    }

    /* ===== MODAL CHANGEMENT STATUT ===== */
    .modal-status {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-status.active {
        display: flex;
    }

    .modal-status .modal-content {
        background: white;
        border-radius: 16px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-status .modal-content h3 {
        font-size: 18px;
        color: #2D3436;
        margin-bottom: 16px;
        text-align: center;
    }

    .modal-status .modal-content select {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 2px solid #E8ECF1;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        margin-bottom: 16px;
        background: white;
    }

    .modal-status .modal-content select:focus {
        border-color: #6C3CE1;
    }

    .modal-status .modal-content .btn-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .modal-status .modal-content .btn-group .btn {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }

    .modal-status .modal-content .btn-group .btn-cancel {
        background: #F0F2F5;
        color: #2D3436;
    }

    .modal-status .modal-content .btn-group .btn-cancel:hover {
        background: #E8ECF1;
    }

    .modal-status .modal-content .btn-group .btn-save {
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
    }

    .modal-status .modal-content .btn-group .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
    }

    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
        padding: 16px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .pagination-info .info {
        font-size: 13px;
        color: #95A5A6;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95A5A6;
    }

    .empty-state .icon {
        font-size: 48px;
        display: block;
        margin-bottom: 16px;
        color: #D4D4D4;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .stats-mini {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-mini {
            padding: 12px;
        }

        .stat-mini .number {
            font-size: 18px;
        }

        .filters {
            flex-direction: column;
            align-items: stretch;
        }

        .filters form {
            flex-direction: column;
            width: 100%;
        }

        .filters form input,
        .filters form select {
            width: 100%;
        }

        .filters .btn-primary,
        .filters .btn-secondary,
        .filters .btn-export {
            width: 100%;
            justify-content: center;
        }

        .table-custom {
            font-size: 12px;
            min-width: 600px;
        }

        .table-custom th,
        .table-custom td {
            padding: 10px 12px;
        }

        .pagination-info {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-mini {
            grid-template-columns: 1fr 1fr;
        }

        .stat-mini .number {
            font-size: 16px;
        }

        .stat-mini .label {
            font-size: 10px;
        }
    }
</style>

<!-- ===== STATS ===== -->
<div class="stats-mini">
    <div class="stat-mini total">
        <span class="icon"></span>
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">Total commandes</div>
    </div>
    <div class="stat-mini attente">
        <span class="icon"></span>
        <div class="number">{{ $stats['en_attente'] }}</div>
        <div class="label">En attente</div>
    </div>
    <div class="stat-mini cours">
        <span class="icon"></span>
        <div class="number">{{ $stats['en_cours'] }}</div>
        <div class="label">En cours</div>
    </div>
    <div class="stat-mini livree">
        <span class="icon"></span>
        <div class="number">{{ $stats['livree'] }}</div>
        <div class="label">Livrées</div>
    </div>
    <div class="stat-mini annulee">
        <span class="icon"></span>
        <div class="number">{{ $stats['annulee'] }}</div>
        <div class="label">Annulées</div>
    </div>
    <div class="stat-mini ventes">
        <span class="icon"></span>
        <div class="number">{{ number_format($stats['total_ventes'], 0) }}</div>
        <div class="label">Total ventes (FC)</div>
    </div>
</div>

<!-- ===== FILTRES ===== -->
<div class="filters">
    <form action="{{ route('artisan.commandes.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1;">
        <input type="text" name="search" placeholder="Rechercher par référence..." value="{{ request('search') }}"
               style="flex:1;min-width:150px;">

        <select name="statut">
            <option value="">Tous les statuts</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $statut)) }}
                </option>
            @endforeach
        </select>

        <select name="client">
            <option value="">Tous les clients</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_debut" value="{{ request('date_debut') }}" style="max-width:140px;">
        <span style="color:#95A5A6;">à</span>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}" style="max-width:140px;">

        <button type="submit" class="btn-primary">
            <i class="fas fa-filter"></i> Filtrer
        </button>
        <a href="{{ route('artisan.commandes.index') }}" class="btn-secondary">
            <i class="fas fa-undo"></i> Réinitialiser
        </a>
    </form>

    <a href="{{ route('artisan.commandes.export', request()->all()) }}" class="btn-export">
        <i class="fas fa-file-export"></i> Exporter
    </a>
</div>

<!-- ===== TABLEAU ===== -->
<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align:center;width:140px;min-width:140px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commandes as $commande)
                <tr>
                    <td>
                        <a href="{{ route('artisan.commandes.show', $commande->id) }}" class="reference">
                            #{{ $commande->reference }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $commande->user->name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:#95A5A6;">{{ $commande->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <strong>{{ number_format($commande->total_ttc, 0) }} FC</strong>
                    </td>
                    <td>
                        <span class="badge-status {{ str_replace(' ', '_', $commande->statut) }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:#95A5A6;">
                        {{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}
                        <div style="font-size:11px;">{{ $commande->created_at->locale('fr')->isoFormat('HH:mm') }}</div>
                    </td>
                    <td style="text-align:center;">
                        <div class="actions">
                            <!-- Voir -->
                            <a href="{{ route('artisan.commandes.show', $commande->id) }}" class="action-btn view" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Changer statut (uniquement pour les commandes non livrées/annulées) -->
                            @if(!in_array($commande->statut, ['livree', 'annulee', 'rembourse']))
                                <form action="{{ route('artisan.commandes.update-status', $commande->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="statut" value="">
                                    <button type="button" class="action-btn status" title="Changer statut" onclick="openStatusModal({{ $commande->id }}, '{{ $commande->statut }}')">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <span class="icon">📭</span>
                            <h3>Aucune commande trouvée</h3>
                            <p>Aucune commande ne correspond à vos critères</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ===== MODAL CHANGEMENT STATUT ===== -->
<div class="modal-status" id="statusModal">
    <div class="modal-content">
        <h3>Changer le statut</h3>
        <form id="statusForm" method="POST">
            @csrf
            <select name="statut" id="statusSelect">
                <option value="en_attente">En attente</option>
                <option value="en_cours">En cours</option>
                <option value="expediee">Expédiée</option>
                <option value="payer">Payer</option>
                <option value="livree">Livrée</option>
                <option value="annulee">Annulée</option>
                <option value="rembourse">Remboursée</option>
            </select>
            <div class="btn-group">
                <button type="button" class="btn btn-cancel" onclick="closeStatusModal()">Annuler</button>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== PAGINATION ===== -->
<div class="pagination-info">
    <div class="info">
        Affichage de {{ $commandes->firstItem() ?? 0 }} à {{ $commandes->lastItem() ?? 0 }} sur {{ $commandes->total() }} commandes
    </div>
    <div>
        {{ $commandes->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function openStatusModal(commandeId, currentStatus) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        const select = document.getElementById('statusSelect');

        form.action = '/artisan/commandes/' + commandeId + '/status';
        select.value = currentStatus;

        modal.classList.add('active');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('active');
    }

    // Fermer la modal en cliquant à l'extérieur
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
</script>

@endsection