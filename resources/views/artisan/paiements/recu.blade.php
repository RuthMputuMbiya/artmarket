<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement - {{ $paiement->reference }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: white;
            padding: 40px;
            color: #1f2937;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            color: #1a1a2e;
            font-weight: 700;
        }
        .header .sub {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        .header .ref {
            font-size: 18px;
            font-weight: 700;
            color: #4f46e5;
            margin-top: 8px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 20px 0;
        }
        .info-item {
            background: #f9fafb;
            padding: 10px 14px;
            border-radius: 6px;
        }
        .info-item .label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-item .value {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            margin-top: 2px;
        }
        .montant {
            text-align: center;
            padding: 20px;
            background: #eef2ff;
            border-radius: 8px;
            margin: 20px 0;
        }
        .montant .amount {
            font-size: 36px;
            font-weight: 700;
            color: #4f46e5;
        }
        .montant .label {
            font-size: 14px;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            background: #d1fae5;
            color: #059669;
        }
        @media (max-width: 600px) {
            body { padding: 20px; }
            .receipt { padding: 20px; }
            .info-grid { grid-template-columns: 1fr; }
            .montant .amount { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- HEADER -->
        <div class="header">
            <h1>ArtMarket</h1>
            <div class="sub">Reçu de paiement</div>
            <div class="ref">Réf: {{ $paiement->reference }}</div>
        </div>

        <!-- STATUT -->
        <div style="text-align:center;margin-bottom:20px;">
            <span class="status-badge">✓ Paiement validé</span>
        </div>

        <!-- MONTANT -->
        <div class="montant">
            <div class="label">Montant payé</div>
            <div class="amount">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($paiement->montant, 2) }}</div>
        </div>

        <!-- INFOS -->
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Client</div>
                <div class="value">{{ $paiement->commande->user->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Email</div>
                <div class="value">{{ $paiement->commande->user->email ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Commande</div>
                <div class="value">#{{ $paiement->commande->reference ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Date</div>
                <div class="value">{{ \Carbon\Carbon::parse($paiement->date_paiement ?? $paiement->created_at)->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</div>
            </div>
            <div class="info-item">
                <div class="label">Méthode</div>
                <div class="value">{{ ucfirst(str_replace('_', ' ', $paiement->methode)) }}</div>
            </div>
            <div class="info-item">
                <div class="label">Transaction ID</div>
                <div class="value">{{ $paiement->transaction_id ?? 'Non disponible' }}</div>
            </div>
        </div>

        <!-- ARTICLES -->
        @if($commande->lignes->count() > 0)
            <div style="margin:20px 0;">
                <h3 style="font-size:14px;color:#6b7280;margin-bottom:8px;text-transform:uppercase;letter-spacing:0.3px;">Articles</h3>
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                            <th style="padding:8px 12px;text-align:left;">Produit</th>
                            <th style="padding:8px 12px;text-align:center;">Qté</th>
                            <th style="padding:8px 12px;text-align:right;">Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commande->lignes as $ligne)
                            <tr style="border-bottom:1px solid #f3f4f6;">
                                <td style="padding:8px 12px;">{{ $ligne->oeuvre->nom ?? 'N/A' }}</td>
                                <td style="padding:8px 12px;text-align:center;">{{ $ligne->quantite }}</td>
                                <td style="padding:8px 12px;text-align:right;">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($ligne->prix_ttc, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="font-weight:700;">
                            <td colspan="2" style="padding:8px 12px;text-align:right;">Total</td>
                            <td style="padding:8px 12px;text-align:right;">{{ $paiement->devise->symbole ?? 'FC' }} {{ number_format($commande->total_ttc, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p style="margin-top:4px;">Ce reçu fait foi de paiement.</p>
            <p style="margin-top:8px;font-size:10px;">Généré le {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('DD MMM YYYY à HH:mm') }}</p>
        </div>
    </div>
</body>
</html>