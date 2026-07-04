@extends('layouts.client')

@section('title', 'Notification - ArtMarket')

@section('content')

<style>
    .notification-detail {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        max-width: 700px;
        margin: 0 auto;
    }

    .notification-detail .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .notification-detail .header .icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .notification-detail .header .icon.green { background: #D4F4E6; color: #2ECC71; }
    .notification-detail .header .icon.purple { background: #E8E0F8; color: #6C3CE1; }
    .notification-detail .header .icon.orange { background: #FDE8D0; color: #F39C12; }
    .notification-detail .header .icon.red { background: #FCD4D4; color: #E74C3C; }
    .notification-detail .header .icon.blue { background: #D4E8FC; color: #3498DB; }

    .notification-detail .header .titre {
        font-size: 20px;
        font-weight: 700;
        color: #2D3436;
        flex: 1;
    }

    .notification-detail .header .date {
        font-size: 13px;
        color: #95A5A6;
    }

    .notification-detail .message {
        font-size: 16px;
        color: #2D3436;
        line-height: 1.8;
        padding: 16px 0;
        border-top: 1px solid #F0F0F0;
        border-bottom: 1px solid #F0F0F0;
        margin: 12px 0;
    }

    .notification-detail .meta {
        font-size: 13px;
        color: #95A5A6;
    }

    .notification-detail .meta .label {
        font-weight: 600;
        color: #2D3436;
    }

    .notification-detail .actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.lu { background: #F0F0F0; color: #7F8C8D; }
    .badge-status.non_lu { background: #6C3CE1; color: white; }
</style>

<div class="notification-detail">
    <div class="header">
        <div class="icon
            @switch($notification->type)
                @case('paiement_confirme') green @break
                @case('nouvelle_commande') purple @break
                @case('commande_expediee') blue @break
                @case('commande_livree') green @break
                @case('commande_annulee') red @break
                @case('stock_alerte') orange @break
                @default purple
            @endswitch
        ">
            <i class="fas
                @switch($notification->type)
                    @case('paiement_confirme') fa-credit-card @break
                    @case('nouvelle_commande') fa-shopping-bag @break
                    @case('commande_expediee') fa-truck @break
                    @case('commande_livree') fa-check-circle @break
                    @case('commande_annulee') fa-times-circle @break
                    @case('stock_alerte') fa-exclamation-triangle @break
                    @default fa-bell
                @endswitch
            "></i>
        </div>
        <div class="titre">{{ $notification->titre }}</div>
        <div class="date">{{ \Carbon\Carbon::parse($notification->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</div>
    </div>

    <div class="message">
        {{ $notification->message }}
    </div>

    <div class="meta">
        <div><span class="label">Type :</span> {{ ucfirst(str_replace('_', ' ', $notification->type)) }}</div>
        <div style="margin-top:4px;">
            <span class="label">Statut :</span>
            <span class="badge-status {{ $notification->is_read ? 'lu' : 'non_lu' }}">
                {{ $notification->is_read ? '✅ Lu' : '📩 Non lu' }}
            </span>
            @if($notification->is_read)
                <span style="font-size:12px;color:#95A5A6;margin-left:8px;">
                    (Lu le {{ \Carbon\Carbon::parse($notification->read_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }})
                </span>
            @endif
        </div>
    </div>

    <div class="actions">
        <a href="{{ route('client.notifications.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        @if(!$notification->is_read)
            <a href="{{ route('client.notifications.mark-read', $notification->id) }}" class="btn-primary">
                <i class="fas fa-check"></i> Marquer comme lu
            </a>
        @endif
        <form action="{{ route('client.notifications.destroy', $notification->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cette notification ?')">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>

@endsection