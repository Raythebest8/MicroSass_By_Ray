<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement #{{ $paiement->reference_transaction }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .company-info { text-align: left; }
        .receipt-title { text-align: right; color: #4f46e5; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 40px; }
        .details-table th { background: #f3f4f6; text-align: left; padding: 10px; }
        .details-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { margin-top: 30px; text-align: right; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.8em; color: #777; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h2>Procredit</h2>
                <p>Lomé, Togo<br>Contact: support@votre-site.com</p>
            </div>
            <div class="receipt-title">
                <h1>REÇU DE PAIEMENT</h1>
                <p>Date: {{ $paiement->date_paiement->format('d/m/Y') }}</p>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>Client:</strong> {{ $paiement->user->nom }} {{ $paiement->user->prenom }}</p>
            <p><strong>Référence Transaction:</strong> {{ $paiement->reference_transaction }}</p>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Méthode</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Remboursement Prêt - {{ $paiement->echeance->demande->motif ?? 'Prêt' }}</td>
                    <td>{{ $paiement->methode_paiement }}</td>
                    <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total Payé : {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
        </div>

        <div class="footer">
            <p>Ce document est un reçu officiel de paiement en ligne.<br>Merci pour votre confiance !</p>
        </div>
    </div>
</body>
</html>