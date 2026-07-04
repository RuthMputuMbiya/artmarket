@extends('layouts.client')

@section('title', 'Paiement - ArtMarket')

@section('content')

<style>
    .payment-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .page-header p {
        color: #95A5A6;
        font-size: 16px;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
    }

    .card-modern {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        border: 1px solid rgba(108,60,225,0.08);
        transition: all 0.3s ease;
    }

    .card-modern:hover {
        box-shadow: 0 15px 50px rgba(108,60,225,0.10);
        transform: translateY(-2px);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .recap-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }

    .recap-item:last-child {
        border-bottom: none;
    }

    .recap-item .label {
        color: #7f8c8d;
        font-size: 14px;
    }

    .recap-item .value {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 14px;
    }

    .recap-item.total {
        margin-top: 8px;
        padding-top: 16px;
        border-top: 2px solid #6C3CE1;
    }

    .recap-item.total .label {
        font-weight: 700;
        color: #1a1a2e;
        font-size: 16px;
    }

    .recap-item.total .value {
        font-size: 24px;
        color: #6C3CE1;
        font-weight: 800;
    }

    .recap-articles {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f2f5;
    }

    .recap-articles .label-articles {
        font-size: 13px;
        color: #95A5A6;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .article-item {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 13px;
        color: #2d3436;
    }

    .article-item .qty {
        display: inline-block;
        background: #f0f2f5;
        padding: 0 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #6C3CE1;
        margin-left: 8px;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }

    .payment-method {
        padding: 16px 10px;
        border: 2px solid #f0f2f5;
        border-radius: 14px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: white;
        position: relative;
    }

    .payment-method input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .payment-method .logo {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 50px;
        margin-bottom: 6px;
    }

    .payment-method .logo img {
        max-height: 40px;
        max-width: 80px;
        object-fit: contain;
    }

    .payment-method .name {
        font-size: 12px;
        font-weight: 600;
        color: #2d3436;
    }

    .payment-method:hover {
        border-color: #6C3CE1;
        background: #f8f6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(108,60,225,0.10);
    }

    .payment-method.active {
        border-color: #6C3CE1;
        background: #f8f6ff;
        box-shadow: 0 4px 15px rgba(108,60,225,0.15);
    }

    .payment-method .check {
        display: none;
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: #6C3CE1;
        border-radius: 50%;
        color: white;
        font-size: 12px;
        align-items: center;
        justify-content: center;
    }

    .payment-method.active .check {
        display: flex;
    }

    .phone-group {
        margin-bottom: 20px;
    }

    .phone-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .phone-group label i {
        color: #6C3CE1;
        margin-right: 6px;
    }

    .phone-input-wrapper {
        display: flex;
        align-items: center;
        border: 2px solid #f0f2f5;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .phone-input-wrapper:focus-within {
        border-color: #6C3CE1;
        box-shadow: 0 0 0 4px rgba(108,60,225,0.1);
    }

    .phone-input-wrapper .prefix {
        padding: 12px 14px;
        background: #f8f6ff;
        font-weight: 700;
        color: #6C3CE1;
        font-size: 14px;
        border-right: 2px solid #f0f2f5;
        white-space: nowrap;
    }

    .phone-input-wrapper input {
        flex: 1;
        padding: 12px 16px;
        border: none;
        outline: none;
        font-size: 15px;
        font-family: 'Inter', 'Poppins', sans-serif;
        background: transparent;
        color: #1a1a2e;
    }

    .phone-input-wrapper input::placeholder {
        color: #b0b0b0;
    }

    .info-box {
        background: #f8f6ff;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 13px;
        color: #6C3CE1;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .info-box i {
        font-size: 20px;
    }

    .btn-pay-modern {
        width: 100%;
        padding: 18px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #6C3CE1, #5A2FC2);
        color: white;
        font-weight: 700;
        font-size: 18px;
        font-family: 'Inter', 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .btn-pay-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(108,60,225,0.40);
    }

    .btn-pay-modern:active {
        transform: translateY(0);
    }

    .btn-pay-modern i {
        font-size: 20px;
    }

    .btn-pay-modern .amount {
        font-size: 20px;
    }

    .btn-pay-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .security-badge-modern {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        padding: 12px;
        font-size: 12px;
        color: #95A5A6;
        border-top: 1px solid #f0f2f5;
    }

    .security-badge-modern span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .security-badge-modern i {
        color: #2ECC71;
    }

    .error-message {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 6px;
        display: none;
        align-items: center;
        gap: 6px;
    }

    .error-message.show {
        display: flex;
    }

    @media (max-width: 768px) {
        .payment-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .card-modern {
            padding: 20px;
        }

        .payment-methods {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .recap-item.total .value {
            font-size: 20px;
        }

        .btn-pay-modern {
            font-size: 16px;
            padding: 15px;
        }

        .payment-method .logo {
            height: 40px;
        }

        .payment-method .logo img {
            max-height: 30px;
        }
    }

    @media (max-width: 480px) {
        .payment-methods {
            grid-template-columns: 1fr 1fr;
        }

        .payment-method {
            padding: 12px 8px;
        }

        .payment-method .logo {
            height: 35px;
        }

        .payment-method .logo img {
            max-height: 25px;
        }

        .payment-method .name {
            font-size: 10px;
        }

        .phone-input-wrapper .prefix {
            padding: 10px 10px;
            font-size: 12px;
        }

        .phone-input-wrapper input {
            padding: 10px 12px;
            font-size: 14px;
        }
    }

    .spinner {
        display: none;
        width: 24px;
        height: 24px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s ease-in-out infinite;
    }

    .spinner.show {
        display: inline-block;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="payment-wrapper">

    <div class="page-header">
        <h1>Paiement Mobile Money</h1>
        <p>Payez votre commande en toute securite via Mobile Money</p>
    </div>

    <div class="payment-grid">

        <div class="card-modern">
            <div class="card-title">
                <span class="icon"><i class="fas fa-receipt"></i></span>
                Recapitulatif de la commande
            </div>

            <div class="recap-item">
                <span class="label">Commande</span>
                <span class="value">#{{ $commande->reference }}</span>
            </div>
            <div class="recap-item">
                <span class="label">Date</span>
                <span class="value">{{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}</span>
            </div>
            <div class="recap-item">
                <span class="label">Sous-total</span>
                <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ht, 2) }}</span>
            </div>

            @if($commande->commission > 0)
            <div class="recap-item">
                <span class="label">Commission (5%)</span>
                <span class="value" style="color:#95A5A6;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->commission, 2) }}</span>
            </div>
            @endif

            <div class="recap-item total">
                <span class="label">Total TTC</span>
                <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
            </div>

            <div class="recap-articles">
                <div class="label-articles">
                    <i class="fas fa-box"></i> Articles commandes ({{ $commande->lignes->count() }})
                </div>
                @foreach($commande->lignes as $ligne)
                    <div class="article-item">
                        <span>
                            {{ $ligne->oeuvre->titre }}
                            <span class="qty">x{{ $ligne->quantite }}</span>
                        </span>
                        <span>{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-modern">
            <div class="card-title">
                <span class="icon"><i class="fas fa-mobile-alt"></i></span>
                Choisissez votre operateur
            </div>

            <form id="paymentForm" action="{{ route('client.paiement.initiate') }}" method="POST">
                @csrf
                <input type="hidden" name="commande_id" value="{{ $commande->id }}">
                <input type="hidden" name="paiement_id" value="{{ $paiement->id ?? '' }}">

                <div class="payment-methods">
                    <label class="payment-method active" id="method_orange_money">
                        <input type="radio" name="mode_paiement" value="orange_money" checked>
                        <span class="check">✓</span>
                        <div class="logo">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/13/Orange_Money_Logo.svg/1280px-Orange_Money_Logo.svg.png" 
                                 alt="Orange Money" 
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2240%22%3E%3Crect width=%2280%22 height=%2240%22 fill=%22%23FF7F00%22/%3E%3Ctext x=%225%22 y=%2225%22 fill=%22white%22 font-size=%2214%22 font-weight=%22bold%22%3EOrange%3C/text%3E%3C/svg%3E'">
                        </div>
                        <div class="name">Orange Money</div>
                    </label>

                    <label class="payment-method" id="method_airtel_money">
                        <input type="radio" name="mode_paiement" value="airtel_money">
                        <span class="check">✓</span>
                        <div class="logo">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Airtel_logo.svg/1280px-Airtel_logo.svg.png" 
                                 alt="Airtel Money" 
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2240%22%3E%3Crect width=%2280%22 height=%2240%22 fill=%22%23E60000%22/%3E%3Ctext x=%225%22 y=%2225%22 fill=%22white%22 font-size=%2214%22 font-weight=%22bold%22%3EAirtel%3C/text%3E%3C/svg%3E'">
                        </div>
                        <div class="name">Airtel Money</div>
                    </label>

                    <label class="payment-method" id="method_mpesa">
                        <input type="radio" name="mode_paiement" value="mpesa">
                        <span class="check">✓</span>
                        <div class="logo">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/M-Pesa_logo.svg/1280px-M-Pesa_logo.svg.png" 
                                 alt="M-Pesa" 
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2240%22%3E%3Crect width=%2280%22 height=%2240%22 fill=%22%2300A651%22/%3E%3Ctext x=%225%22 y=%2225%22 fill=%22white%22 font-size=%2214%22 font-weight=%22bold%22%3EMPesa%3C/text%3E%3C/svg%3E'">
                        </div>
                        <div class="name">M-Pesa</div>
                    </label>
                </div>

                <div class="phone-group">
                    <label>
                        <i class="fas fa-phone"></i>
                        Numero de telephone
                        <span style="font-weight:400;color:#95A5A6;font-size:12px;">(pour recevoir la demande de paiement)</span>
                    </label>
                    <div class="phone-input-wrapper">
                        <span class="prefix">+243</span>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone"
                            placeholder="8X XXX XXXX" 
                            value="{{ auth()->user()->phone ?? auth()->user()->telephone ?? '' }}"
                            required
                            maxlength="10"
                            minlength="9"
                            autocomplete="tel"
                        >
                    </div>
                    <div class="error-message" id="phoneError">
                        <i class="fas fa-exclamation-circle"></i> 
                        Veuillez entrer un numero de telephone valide (9 a 10 chiffres)
                    </div>
                </div>

                <div class="info-box">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Marché artistiques Paiement</strong>
                        <div style="font-size:12px;color:#7f8c8d;margin-top:2px;">
                            
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-pay-modern" id="payBtn">
                    <i class="fas fa-credit-card"></i>
                    <span id="payText">Payer</span>
                    <span class="amount" id="payAmount">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
                    <span class="spinner" id="paySpinner"></span>
                </button>
            </form>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const methods = document.querySelectorAll('.payment-method');
    
    methods.forEach(method => {
        method.addEventListener('click', function() {
            methods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });

    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');
    const payBtn = document.getElementById('payBtn');
    const payText = document.getElementById('payText');
    const paySpinner = document.getElementById('paySpinner');
    const payAmount = document.getElementById('payAmount');
    const form = document.getElementById('paymentForm');

    phoneInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
        validatePhone();
    });

    function validatePhone() {
        const phone = phoneInput.value;
        const isValid = phone.length >= 9 && phone.length <= 10;
        
        if (phone.length > 0 && !isValid) {
            phoneError.classList.add('show');
            payBtn.disabled = true;
            return false;
        } else {
            phoneError.classList.remove('show');
            payBtn.disabled = false;
            return true;
        }
    }

    form.addEventListener('submit', function(e) {
        if (!validatePhone()) {
            e.preventDefault();
            phoneInput.focus();
            return false;
        }

        payBtn.disabled = true;
        payText.textContent = 'Traitement...';
        payAmount.style.display = 'none';
        paySpinner.classList.add('show');
    });

    if (phoneInput.value) {
        validatePhone();
    }
});
</script>
@endpush