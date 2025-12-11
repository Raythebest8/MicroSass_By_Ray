<?php
namespace App\Services;

use App\Models\Echeance;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AmortizationService
{
    /**
     * Génère et enregistre le tableau d'amortissement (échéances) pour une demande de prêt validée.
     *
     * @param Model $demande La demande de prêt (Entreprise ou Particulier)
     * @param float $tauxAnnuel Le taux d'intérêt annuel (ex: 0.10 pour 10%)
     * @param Carbon $dateDebut La date du premier paiement (souvent le mois prochain)
     * @return void
     */
    public function generate(Model $demande, float $tauxAnnuel, Carbon $dateDebut): void
    {
        $montantEmprunte = $demande->montant_souhaite;
        $dureeMois = $demande->duree_mois;
        
        // Calcul du taux mensuel
        $tauxMensuel = $tauxAnnuel / 12;

        // Formule pour calculer l'annuité (paiement mensuel constant A)
        // A = P * [ i * (1 + i)^n ] / [ (1 + i)^n - 1 ]
        // P = Montant Emprunté, i = Taux Mensuel, n = Durée en Mois
        if ($tauxMensuel > 0) {
            $annuite = $montantEmprunte * (
                $tauxMensuel * pow((1 + $tauxMensuel), $dureeMois)
            ) / (
                pow((1 + $tauxMensuel), $dureeMois) - 1
            );
        } else {
            // Prêt sans intérêt (cas rare, paiement principal divisé par la durée)
            $annuite = $montantEmprunte / $dureeMois; 
        }

        // Arrondir l'annuité à la valeur supérieure pour éviter les décimales complexes
        $annuite = ceil($annuite); 
        $capitalRestant = $montantEmprunte;
        $datePaiement = $dateDebut->copy();

        // S'assurer que les anciennes échéances sont supprimées si le prêt est recalculé
        $demande->echeances()->delete();

        // Génération du tableau
        for ($i = 1; $i <= $dureeMois; $i++) {
            
            $interetDu = $capitalRestant * $tauxMensuel;
            
            // Le principal est l'annuité moins l'intérêt
            $principalRembourse = $annuite - $interetDu;

            // Ajustement pour le dernier mois
            if ($i == $dureeMois) {
                // S'assurer que le principal couvre exactement le reste du capital
                $principalRembourse = $capitalRestant;
                $annuite = $principalRembourse + $interetDu;
            }

            $capitalRestant -= $principalRembourse;

            // Création de l'enregistrement de l'échéance
            $demande->echeances()->create([
                'mois_numero'       => $i,
                'date_prevue'       => $datePaiement,
                'montant_principal' => (int) round($principalRembourse),
                'montant_interet'   => (int) round($interetDu),
                'montant_total'     => (int) round($annuite),
                'statut'            => 'à payer',
            ]);

            // Passer au mois suivant pour la date de paiement
            $datePaiement->addMonthNoOverflow();
        }
    }
}