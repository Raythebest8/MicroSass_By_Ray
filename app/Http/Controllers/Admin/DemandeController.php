<?php

use App\Models\Particulier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDemandeController extends Controller
{
    //  (méthodes pour afficher la liste et les détails des demandes)
    


    public function approuverDemande(Request $request, Particulier $demande)
    {
        // 1. Validation : Vérifier si l'utilisateur est bien un administrateur
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        // 2. Vérification du statut actuel
        if ($demande->statut !== 'en attente' && $demande->statut !== 'en cours d\'examen') {
            return back()->with('error', 'Le statut actuel de la demande ne permet pas cette action.');
        }

        // 3. Mise à jour de la base de données
        try {
            $demande->update([
                'statut' => 'approuvé', // Le statut final qui rend le prêt actif
                'admin_id' => Auth::id(), // Enregistre quel admin a validé
                'date_traitement' => now(), // Date et heure de la validation
            ]);

            // 4. Logique d'activation du prêt
            // ICI, vous ajouteriez la logique métier la plus importante :
            // * Création de l'échéancier de remboursement.
            // * Envoi de l'ordre de virement/verdict final à l'utilisateur.
            
            $this->creerEcheancierRemboursement($demande);
            
            // 5. Notification à l'utilisateur
            // (Utilisation d'une notification Laravel ou d'un mail)
            // $demande->user->notify(new LoanApprovedNotification($demande));


            return redirect()
                ->route('admin.demandes.index') // Route du tableau de bord admin
                ->with('success', 'La demande N° ' . $demande->id . ' a été approuvée et le prêt est actif.');
        
        } catch (\Exception $e) {
            // Gérer les erreurs de DB ou de logique métier
            return back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }
    
    /**
     * Méthode fictive pour créer l'échéancier (logique métier)
     */
    protected function creerEcheancierRemboursement(Particulier $demande)
    {
        // Votre code ici pour calculer les montants mensuels (capital + intérêt) 
        // et enregistrer chaque paiement futur dans une table 'echeances'.
    }

}