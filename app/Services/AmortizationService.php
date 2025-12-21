<?php
namespace App\Services;

use App\Models\Echeance;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AmortizationService
{
    /**
     * Génère et enregistre le tableau d'amortissement.
     *
     * @param Model $demande
     * @param float $tauxAnnuel
     * @param Carbon|string $dateDebut  <-- On accepte les deux types ici
     * @return void
     */
    public function generate(Model $demande, float $tauxAnnuel, $dateDebut): void
    {
        // CORRECTION : Convertit en objet Carbon si c'est une chaîne de caractères
        if (!$dateDebut instanceof Carbon) {
            $dateDebut = Carbon::parse($dateDebut);
        }

        // On divise le taux annuel par 100 si l'admin a saisi "23" pour 23%
        $tauxReelAnnuel = $tauxAnnuel > 1 ? ($tauxAnnuel / 100) : $tauxAnnuel;

        $montantEmprunte = $demande->montant_souhaite;
        $dureeMois = $demande->duree_mois;
        
        // Calcul du taux mensuel
        $tauxMensuel = $tauxReelAnnuel / 12;

        // Formule de l'annuité constante (Formule bancaire standard)
        // 
        if ($tauxMensuel > 0) {
            $annuite = $montantEmprunte * (
                $tauxMensuel * pow((1 + $tauxMensuel), $dureeMois)
            ) / (
                pow((1 + $tauxMensuel), $dureeMois) - 1
            );
        } else {
            $annuite = $montantEmprunte / $dureeMois; 
        }

        $annuite = ceil($annuite); 
        $capitalRestant = $montantEmprunte;
        $datePaiement = $dateDebut->copy();

        // Nettoyage des anciennes échéances
        $demande->echeances()->delete();

        for ($i = 1; $i <= $dureeMois; $i++) {
            $interetDu = $capitalRestant * $tauxMensuel;
            $principalRembourse = $annuite - $interetDu;

            if ($i == $dureeMois) {
                $principalRembourse = $capitalRestant;
                $annuite = $principalRembourse + $interetDu;
            }

            $capitalRestant -= $principalRembourse;

            $demande->echeances()->create([
                'mois_numero'       => $i,
                'date_prevue'       => $datePaiement,
                'montant_principal' => (int) round($principalRembourse),
                'montant_interet'   => (int) round($interetDu),
                'montant_total'     => (int) round($annuite),
                'statut'            => 'à payer',
            ]);

            $datePaiement->addMonthNoOverflow();
        }
    }
}