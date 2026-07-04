@extends('layouts.client')

@section('title', 'Mon panier - ArtMarket')

@section('content')

<style>
    .cart-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        margin-bottom: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .cart-item img {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #E8ECF1;
        flex-shrink: 0;
    }

    .cart-item .info {
        flex: 1;
    }

    .cart-item .info .titre {
        font-weight: 600;
        color: #2D3436;
        font-size: 16px;
    }

    .cart-item .info .artisan {
        font-size: 13px;
        color: #95A5A6;
    }

    .cart-item .info .prix {
        font-weight: 700;
        color: #6C3CE1;
        font-size: 18px;
        margin-top: 4px;
    }

    .cart-item .quantite {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cart-item .quantite button {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #E8ECF1;
        background: white;
        color: #2D3436;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-item .quantite button:hover {
        border-color: #6C3CE1;
        color: #6C3CE1;
    }

    .cart-item .quantite input {
        width: 50px;
        text-align: center;
        padding: 6px;
        border: 2px solid #E8ECF1;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: all 0.3s ease;
    }

    .cart-item .quantite input:focus {
        border-color: #6C3CE1;
    }

    .cart-item .total-item {
        font-weight: 700;
        color: #6C3CE1;
        font-size: 18px;
        min-width: 100px;
        text-align: right;
    }

    .cart-item .remove-btn {
        background: none;
        border: none;
        color: #E74C3C;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 4px;
    }

    .cart-item .remove-btn:hover {
        transform: scale(1.2);
    }

    .cart-summary {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        position: sticky;
        top: 90px;
    }

    .cart-summary h3 {
        font-size: 18px;
        font-weight: 700;
        color: #2D3436;
        margin-bottom: 16px;
    }

    .cart-summary .row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #F0F0F0;
    }

    .cart-summary .row:last-child {
        border-bottom: none;
    }

    .cart-summary .row .label {
        color: #95A5A6;
    }

    .cart-summary .row .value {
        font-weight: 600;
        color: #2D3436;
    }

    .cart-summary .row.total .value {
        font-size: 22px;
        color: #6C3CE1;
        font-weight: 700;
    }

    .btn-checkout {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        font-weight: 700;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 16px;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108,60,225,0.35);
    }

    .btn-checkout:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .empty-cart .icon {
        font-size: 60px;
        margin-bottom: 16px;
    }

    .empty-cart h3 {
        font-size: 20px;
        color: #2D3436;
        margin-bottom: 8px;
    }

    .empty-cart p {
        color: #95A5A6;
        margin-bottom: 16px;
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-wrap: wrap;
        }

        .cart-item .total-item {
            text-align: left;
            min-width: auto;
            width: 100%;
            padding-left: 96px;
        }

        .cart-summary {
            position: relative;
            top: 0;
            margin-top: 16px;
        }
    }

    @media (max-width: 480px) {
        .cart-item img {
            width: 60px;
            height: 60px;
        }

        .cart-item .info .prix {
            font-size: 15px;
        }

        .cart-item .total-item {
            font-size: 15px;
        }
    }
</style>

<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-shopping-cart text-primary mr-3"></i> Mon panier
    </h1>

    @if(count($items) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">
                @foreach($items as $item)
                    <div class="cart-item">
                        <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}" alt="{{ $item['titre'] }}">

                        <div class="info">
                            <div class="titre">{{ $item['titre'] }}</div>
                            <div class="artisan">
                                <i class="fas fa-user"></i> {{ $item['artisan'] }}
                                <span style="margin-left:12px;">
                                    <i class="fas fa-box"></i> {{ $item['stock'] }} disponibles
                                </span>
                            </div>
                            <div class="prix">{{ $item['devise_symbole'] }} {{ number_format($item['prix'], 2) }}</div>
                        </div>

                        <div class="quantite">
                            <form action="{{ route('client.panier.update', $item['key']) }}" method="POST" id="form-{{ $item['key'] }}">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="updateQty('{{ $item['key'] }}', -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantite" value="{{ $item['quantite'] }}"
                                       min="1" max="{{ $item['stock'] }}" id="qty-{{ $item['key'] }}"
                                       onchange="submitForm('{{ $item['key'] }}')">
                                <button type="button" onclick="updateQty('{{ $item['key'] }}', 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>

                        <div class="total-item">{{ $item['devise_symbole'] }} {{ number_format($item['total'], 2) }}</div>

                        <form action="{{ route('client.panier.remove', $item['key']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-btn" onclick="return confirm('Retirer cet article ?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                @endforeach

                <div style="text-align:right;margin-top:12px;">
                    <form action="{{ route('client.panier.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:8px 20px;font-size:13px;" onclick="return confirm('Vider le panier ?')">
                            <i class="fas fa-trash"></i> Vider le panier
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="cart-summary">
                    <h3> Résumé</h3>

                    <div class="row">
                        <span class="label">Sous-total</span>
                        <span class="value">{{ $deviseSymbole }} {{ number_format($total, 2) }}</span>
                    </div>

                    <!-- ✅ SUPPRIMÉ LA LIGNE LIVRAISON -->

                    <div class="row total">
                        <span class="label">Total TTC</span>
                        <span class="value">{{ $deviseSymbole }} {{ number_format($totalTTC, 2) }}</span>
                    </div>


                    <form action="{{ route('client.commande.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-checkout">
                            <i class="fas fa-credit-card"></i> Passer commande
                        </button>
                    </form>

                    <a href="{{ route('client.catalogue') }}" class="btn-secondary" style="width:100%;justify-content:center;margin-top:8px;">
                        <i class="fas fa-compass"></i> Continuer mes achats
                    </a>
                </div>
            </div>

        </div>
    @else
        <div class="empty-cart">
            <div class="icon">🛒</div>
            <h3>Votre panier est vide</h3>
            <p>Découvrez nos œuvres et ajoutez-les à votre panier.</p>
            <a href="{{ route('client.catalogue') }}" class="btn-primary">
                <i class="fas fa-compass"></i> Explorer le catalogue
            </a>
        </div>
    @endif
</div>

<script>
    function updateQty(key, delta) {
        const input = document.getElementById('qty-' + key);
        let val = parseInt(input.value) + delta;
        const max = parseInt(input.getAttribute('max'));
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
        submitForm(key);
    }

    function submitForm(key) {
        document.getElementById('form-' + key).submit();
    }
</script>

@endsection