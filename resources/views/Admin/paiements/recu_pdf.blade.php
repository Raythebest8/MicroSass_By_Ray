<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement #{{ $reference }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .container { padding: 40px; }
        .header { border-bottom: 2px solid #f2f2f2; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #f97316; } /* Orange-500 */
        .title { text-align: right; font-size: 18px; color: #999; text-transform: uppercase; }
        
        .grid { width: 100%; margin-bottom: 40px; }
        .col { vertical-align: top; }
        
        .info-box { background: #fafafa; padding: 20px; border-radius: 10px; }
        .label { font-size: 10px; color: #999; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 14px; font-weight: bold; margin-bottom: 10px; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f9fafb; padding: 12px; font-size: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .table td { padding: 15px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #aaa; }
        .stamp { margin-top: 20px; text-align: right; color: #059669; font-weight: bold; border: 3px solid #059669; display: inline-block; padding: 10px; opacity: 0.6; transform: rotate(-15deg); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table style="width: 100%">
                <tr>
                    <td class="logo">VOTRE LOGO</td>
                    <td class="title">Reçu de Paiement</td>
                </tr>
            </table>
        </div>

        <table class="grid">
            <tr>
                <td class="col" style="width: 50%">
                    <div class="label">Client / Débiteur</div>
                    <div class="value">
                        {{ $echeance->demande->display_name }}<br>
                        <small style="font-weight: normal; color: #666;">
                            Type: {{ $type === 'entreprise' ? '🏢 Structure Professionnelle' : '👤 Particulier' }}
                        </small>
                    </div>
                </td>
                <td class="col" style="width: 50%; text-align: right;">
                    <div class="label">Référence Reçu</div>
                    <div class="value">#{{ $reference }}</div>
                    <div class="label">Date d'émission</div>
                    <div class="value">{{ date('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

        <div class="info-box">
            <table style="width: 100%">
                <tr>
                    <td>
                        <div class="label">Objet du paiement</div>
                        <div class="value">Règlement de l'échéance N°{{ $index }}</div>
                    </td>
                    <td style="text-align: right">
                        <div class="label">Mode de règlement</div>
                        <div class="value">Virement / Espèces</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Base (Principal)</th>
                    <th>Intérêt ({{ $echeance->demande->taux_interet }}%)</th>
                    <th style="text-align: right">Total Payé</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Échéance du {{ \Carbon\Carbon::parse($echeance->date_echeance)->format('d/m/Y') }}</td>
                    <td>{{ number_format($principal, 0, ',', ' ') }} F</td>
                    <td>{{ number_format($interet, 0, ',', ' ') }} F</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($echeance->montant, 0, ',', ' ') }} F</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <div class="label">Reste à payer après ce règlement</div>
            <div style="font-size: 20px; font-weight: black; color: #ef4444;">{{ number_format($solde_restant, 0, ',', ' ') }} FCFA</div>
        </div>

        <div style="text-align: right;">
            <div class="stamp">PAIEMENT VALIDÉ</div>
        </div>

        <div class="footer">
            Ce document sert de preuve officielle de paiement. <br>
            Généré automatiquement par le système de gestion financière le {{ date('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>