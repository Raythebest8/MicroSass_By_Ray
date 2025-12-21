<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Échéancier de Prêt #{{ $demande->user->nom }}</title>
    <style>
        @page { margin: 100px 50px; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Header & Logo Section */
        .header { position: fixed; top: -75px; left: 0px; right: 0px; height: 100px; border-bottom: 2px solid #004a99; }
        .logo-section { float: left; width: 50%; }
        .company-name { font-size: 20px; font-weight: bold; color: #004a99; margin: 0; }
        .slogan { font-style: italic; color: #666; font-size: 10px; margin: 0; }
        .info-section { float: right; width: 45%; text-align: right; font-size: 9px; }

        /* Filigrane / Watermark */
        #watermark { position: fixed; top: 35%; left: 10%; transform: rotate(-45deg); font-size: 70px; color: rgba(200, 200, 200, 0.15); z-index: -1000; }

        /* Contenu principal */
        .title { text-align: center; margin-top: 20px; text-decoration: underline; font-size: 16px; font-weight: bold; color: #000; margin-bottom: 10px; padding-top: 20px; }
        .section-title { background: #004a99; color: white; padding: 5px 10px; font-weight: bold; margin-top: 20px; border-radius: 3px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 0.5px solid #ccc; padding: 7px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; font-weight: bold; }
        .text-right { text-align: right; }
        
        /* Bloc Signatures */
        .signature-container { margin-top: 40px; width: 100%; }
        .signature-column { float: left; width: 30%; text-align: center; margin-right: 3%; }
        .signature-box { margin-top: 8px; height: 90px; border: 1px dashed #999; border-radius: 5px; }
        .stamp-zone { font-size: 8px; color: #ccc; padding-top: 35px; text-transform: uppercase; }
        .clear { clear: both; }

        /* Footer */
        .footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 9px; border-top: 1px solid #eee; padding-top: 10px; color: #777; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <div id="watermark">DOCUMENT PRÉVISIONNEL</div>

    <div class="header">
        <div class="logo-section">
            <p class="company-name">POCREDIT</p>
            <p class="slogan">"L'excellence au service de vos financements"</p>
        </div>
        <div class="info-section">
            <p><strong>Siège Social :</strong> Avenue du Commerce, Lomé, Togo</p>
            <p><strong>Support :</strong> contact@pocredit.tg</p>
            <p><strong>Téléphone :</strong> +228 00 00 00 00</p>
        </div>
    </div>

    <div class="footer">
        <p>Généré automatiquement par le portail client le {{ now()->format('d/m/Y H:i') }} - Page <span class="pagenum"></span></p>
        <p>Ce tableau est une simulation. Il ne constitue un engagement qu'après signatures et cachet officiel.</p>
    </div>

    @php
        // Calculs basés sur le montant souhaité
        $montantInitial = $demande->montant_souhaite ?? $demande->montant_demande;
        $tauxAnnuel = $demande->taux_interet;
        $dureeMois = $demande->duree_mois;
        $totalInterets = $montantInitial * ($tauxAnnuel / 100);
        $totalARembourser = $montantInitial + $totalInterets;
        $mensualite = $totalARembourser / $dureeMois;
        $dateDebut = $demande->updated_at ?? now();
    @endphp

    <div class="title">TABLEAU D'AMORTISSEMENT DU PRÊT</div>

    <div class="section-title">RÉSUMÉ DU FINANCEMENT</div>
    <table>
        <tr>
            <td width="25%"><strong>Nom de l'emprunteur :</strong></td>
            <td width="25%">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</td>
            <td width="25%"><strong>Référence Prêt :</strong></td>
            <td width="25%">REF-{{ str_pad($demande->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td><strong>Montant Souhaité :</strong></td>
            <td style="font-size: 13px; font-weight: bold;">{{ number_format($montantInitial, 0, ',', ' ') }} FCFA</td>
            <td><strong>Taux d'intérêt :</strong></td>
            <td>{{ $tauxAnnuel }} %</td>
        </tr>
        <tr>
            <td><strong>Durée de remboursement :</strong></td>
            <td>{{ $dureeMois }} Mois</td>
            <td><strong>Échéance Mensuelle :</strong></td>
            <td style="color: #004a99; font-weight: bold;">{{ number_format($mensualite, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <div class="section-title">CALENDRIER DES ÉCHÉANCES</div>
    <table>
        <thead>
            <tr>
                <th width="10%">N°</th>
                <th width="25%">Date d'échéance</th>
                <th width="30%" class="text-right">Montant Mensuel</th>
                <th width="35%" class="text-right">Reste à Rembourser</th>
            </tr>
        </thead>
        <tbody>
            @php $resteAPayer = $totalARembourser; @endphp
            @for ($i = 1; $i <= $dureeMois; $i++)
                @php 
                    $resteAPayer -= $mensualite;
                    if($i == $dureeMois) $resteAPayer = 0; 
                @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ \Carbon\Carbon::parse($dateDebut)->addMonths($i)->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($mensualite, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right">{{ number_format(max(0, $resteAPayer), 0, ',', ' ') }} FCFA</td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="2" class="text-right">TOTAL GÉNÉRAL À PAYER :</td>
                <td class="text-right">{{ number_format($totalARembourser, 0, ',', ' ') }} FCFA</td>
                <td class="text-right">---</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-container">
        <div class="signature-column">
            <strong>L'Emprunteur</strong><br>
            <span style="font-size: 8px;">"Lu et Approuvé"</span>
            <div class="signature-box">
                <p style="margin-top: 10px; font-size: 10px;">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <div class="signature-column">
            <strong>La Secrétaire</strong><br>
            <span style="font-size: 8px;">(Visa pour contrôle)</span>
            <div class="signature-box"></div>
        </div>

        <div class="signature-column" style="margin-right: 0;">
            <strong>Le Directeur Général</strong><br>
            <span style="font-size: 8px;">(Signature & Cachet)</span>
            <div class="signature-box">
                <div class="stamp-zone">Zone de Cachet Officiel</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>