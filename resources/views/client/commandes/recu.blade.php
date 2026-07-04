<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de paiement - {{ $commande->reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: white;
            padding: 30px;
            color: #2D3436;
        }

        .receipt {
            max-width: 750px;
            margin: 0 auto;
            border: 2px solid #6C3CE1;
            border-radius: 12px;
            padding: 30px;
            background: white;
            box-shadow: 0 8px 30px rgba(108,60,225,0.10);
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #6C3CE1;
        }

        .header .logo h1 {
            font-size: 24px;
            color: #6C3CE1;
            font-weight: 800;
        }

        .header .logo p {
            font-size: 11px;
            color: #95A5A6;
            margin-top: 2px;
        }

        .header .badge {
            background: #D4F4E6;
            color: #2ECC71;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            border: 2px solid #2ECC71;
        }

        /* ===== TITRE ===== */
        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h2 {
            font-size: 26px;
            color: #2D3436;
            font-weight: 700;
        }

        .title p {
            color: #95A5A6;
            font-size: 13px;
            margin-top: 2px;
        }

        /* ===== INFOS ===== */
        .infos {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
            padding: 15px;
            background: #F8F9FA;
            border-radius: 10px;
            border: 1px solid #E8ECF1;
        }

        .infos .item .label {
            font-size: 10px;
            color: #95A5A6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .infos .item .value {
            font-size: 14px;
            font-weight: 600;
            color: #2D3436;
            margin-top: 2px;
        }

        .infos .item .value.highlight {
            color: #6C3CE1;
        }

        /* ===== TABLEAU ===== */
        .table-title {
            font-size: 15px;
            font-weight: 700;
            color: #2D3436;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .table th {
            text-align: left;
            padding: 8px 8px;
            font-size: 10px;
            text-transform: uppercase;
            color: #95A5A6;
            border-bottom: 2px solid #E8ECF1;
            font-weight: 700;
            background: #F8F9FA;
        }

        .table td {
            padding: 8px 8px;
            border-bottom: 1px solid #F0F0F0;
            font-size: 13px;
        }

        .table .product-name {
            font-weight: 600;
            color: #2D3436;
        }

        .table .product-artisan {
            font-size: 11px;
            color: #95A5A6;
        }

        .table .text-center {
            text-align: center;
        }

        .table .text-right {
            text-align: right;
        }

        .table .total-row td {
            border-top: 2px solid #6C3CE1;
            padding-top: 12px;
            font-weight: 700;
            font-size: 15px;
        }

        .table .total-row .text-right {
            color: #6C3CE1;
            font-size: 17px;
        }

        /* ===== TOTAUX ===== */
        .totals {
            margin-top: 15px;
            padding: 15px;
            background: #F8F9FA;
            border-radius: 10px;
            border: 1px solid #E8ECF1;
        }

        .totals .line {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }

        .totals .line .label {
            color: #95A5A6;
        }

        .totals .line .value {
            font-weight: 600;
            color: #2D3436;
        }

        .totals .grand-total {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 2px solid #6C3CE1;
            font-size: 18px;
        }

        .totals .grand-total .label {
            color: #2D3436;
            font-weight: 700;
        }

        .totals .grand-total .value {
            color: #6C3CE1;
            font-weight: 800;
            font-size: 20px;
        }

        /* ===== PAIEMENT ===== */
        .payment-info {
            margin-top: 15px;
            padding: 12px 16px;
            background: #E8F8F0;
            border-radius: 10px;
            border: 1px solid #D4F4E6;
        }

        .payment-info .line {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 13px;
        }

        .payment-info .line .label {
            color: #2D3436;
            font-weight: 500;
        }

        .payment-info .line .value {
            font-weight: 600;
            color: #2ECC71;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #F0F0F0;
            text-align: center;
        }

        .footer .merci {
            font-size: 16px;
            color: #2D3436;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .footer .merci span {
            color: #6C3CE1;
        }

        .footer .info {
            font-size: 11px;
            color: #95A5A6;
            margin-top: 6px;
            line-height: 1.6;
        }

        .footer .info strong {
            color: #2D3436;
        }

        .footer .signature {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px dashed #E8ECF1;
            font-size: 11px;
            color: #95A5A6;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .receipt {
                padding: 15px;
            }
            .header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            .infos {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 12px;
            }
            .infos .item .value {
                font-size: 12px;
            }
            .table {
                font-size: 11px;
            }
            .table th,
            .table td {
                padding: 6px 4px;
            }
            .totals .grand-total {
                font-size: 15px;
            }
            .payment-info .line {
                font-size: 12px;
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
        }

        @media (max-width: 400px) {
            .infos {
                grid-template-columns: 1fr;
            }
            .table th,
            .table td {
                font-size: 10px;
                padding: 4px 3px;
            }
        }
    </style>
</head>
<body>

<div class="receipt">

    {{-- HEADER --}}
    <div class="header">
        <div class="logo">
            <h1>ArtMarket</h1>
            <p>Marché digital d'oeuvres artistiques</p>
        </div>
        <div class="badge">
            PAYE
        </div>
    </div>

    {{-- TITRE --}}
    <div class="title">
        <h2>RECU DE PAIEMENT</h2>
        <p>Merci pour votre confiance et votre achat</p>
    </div>

    {{-- INFOS --}}
    <div class="infos">
        <div class="item">
            <span class="label">Commande</span>
            <span class="value highlight">#{{ $commande->reference }}</span>
        </div>
        <div class="item">
            <span class="label">Date</span>
            <span class="value">{{ $commande->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
        </div>
        <div class="item">
            <span class="label">Client</span>
            <span class="value">{{ $commande->user->name }}</span>
        </div>
        <div class="item">
            <span class="label">Email</span>
            <span class="value">{{ $commande->user->email }}</span>
        </div>
        <div class="item">
            <span class="label">Telephone</span>
            <span class="value">{{ $paiement->client_telephone ?? $commande->user->phone ?? 'Non renseigne' }}</span>
        </div>
        <div class="item">
            <span class="label">Total TTC</span>
            <span class="value highlight">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>
    </div>

    {{-- TABLEAU PRODUITS --}}
    <div class="table-title">Articles commandes</div>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Artisan</th>
                <th style="text-align:center;">Qte</th>
                <th style="text-align:right;">Prix unitaire</th>
                <th style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->lignes as $ligne)
            <tr>
                <td>
                    <div class="product-name">{{ $ligne->oeuvre->titre }}</div>
                    <div class="product-artisan">Par {{ $ligne->oeuvre->artisan->name ?? 'Artisan' }}</div>
                </td>
                <td>{{ $ligne->oeuvre->artisan->name ?? 'N/A' }}</td>
                <td style="text-align:center;">{{ $ligne->quantite }}</td>
                <td style="text-align:right;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->prix_unitaire, 2) }}</td>
                <td style="text-align:right;font-weight:600;">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($ligne->total, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align:right;">
                    <strong>TOTAL</strong>
                </td>
                <td style="text-align:right;color:#6C3CE1;">
                    <strong>{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <div class="totals">
        <div class="line">
            <span class="label">Sous-total</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ht, 2) }}</span>
        </div>
        @if($commande->commission > 0)
        <div class="line">
            <span class="label">Commission (5%)</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->commission, 2) }}</span>
        </div>
        @endif
        <div class="line">
            <span class="label">Livraison</span>
            <span class="value" style="color:#2ECC71;">Gratuit</span>
        </div>
        <div class="line grand-total">
            <span class="label">TOTAL TTC</span>
            <span class="value">{{ $commande->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</span>
        </div>
    </div>

    {{-- PAIEMENT --}}
    <div class="payment-info">
        <div class="line">
            <span class="label">Mode de paiement</span>
            <span class="value">
                @switch($paiement->methode)
                    @case('orange_money') Orange Money @break
                    @case('airtel_money') Airtel Money @break
                    @case('mpesa') M-Pesa @break
                    @case('carte') Carte bancaire @break
                    @default {{ ucfirst($paiement->methode ?? 'Shwary') }}
                @endswitch
            </span>
        </div>
        <div class="line">
            <span class="label">Telephone de paiement</span>
            <span class="value">{{ $paiement->client_telephone ?? 'Non renseigne' }}</span>
        </div>
        <div class="line">
            <span class="label">Reference paiement</span>
            <span class="value">{{ $paiement->reference }}</span>
        </div>
        <div class="line">
            <span class="label">Date de paiement</span>
            <span class="value">{{ $paiement->date_paiement ? $paiement->date_paiement->locale('fr')->isoFormat('DD MMM YYYY HH:mm') : 'N/A' }}</span>
        </div>
        <div class="line">
            <span class="label">Statut</span>
            <span class="value" style="color:#2ECC71;">Paye</span>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <div class="merci">
            Merci pour votre achat chez <span>ArtMarket</span>
        </div>
        <div class="info">
            <strong>ArtMarket</strong> - Marché digital d'oeuvres artistiques<br>
            Ce document fait office de <strong>reçu de paiement</strong> et de <strong>preuve d'achat</strong>.
        </div>
        <div class="signature">
            Recu genere le {{ now()->locale('fr')->isoFormat('DD MMM YYYY HH:mm') }}
        </div>
    </div>

</div>

</body>
</html>