<?php

namespace App\Http\Controllers;

use App\Models\Particulier;
use App\Models\Entreprise; // A inclure si vous gérez les deux
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\AmortizationService; // 💡 NOUVEAU : Importation du service
use Carbon\Carbon; // 💡 NOUVEAU : Pour la gestion des dates de paiement

class AdminDemandeController extends Controller
{
    // ... (méthodes pour afficher la liste et les détails des demandes) 

    // Injecter le service directement dans la méthode pour qu'il soit disponible
    public function approuverDemande(Request $request, Particulier $demande, AmortizationService $amortizationService)
    {
        // 1. Validation : Vérifier si l'utilisateur est bien un administrateur
        // NOTE: Il est préférable d'utiliser un middleware `->middleware('can:approve-loan')` ou `role:admin` sur la route.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        // 2. Vérification du statut actuel
        if ($demande->statut !== 'en attente' && $demande->statut !== 'en cours d\'examen') {
            return back()->with('error', 'Le statut actuel de la demande ne permet pas cette action.');
        }

        // 3. Récupérer les paramètres du prêt (le taux et la durée sont stockés dans la demande ou fixés)
        // NOTE: Ces valeurs doivent être cohérentes avec les règles de votre institution.
        $tauxAnnuel = $demande->taux_interet ?? 0.08; // Exemple : 8% (doit être stocké dans la DB)
        $datePremierPaiement = Carbon::now()->addMonth()->startOfDay(); // Le premier paiement le mois prochain

        try {
            // 4. Mise à jour du statut dans la base de données
            $demande->update([
                'statut' => 'validée', // J'ai changé 'approuvé' par 'validée' pour utiliser le terme déjà vu dans la vue détails
                'admin_id' => Auth::id(), 
                'date_traitement' => now(), 
            ]);

            // 5. Génération du tableau d'amortissement
            $amortizationService->generate($demande, $tauxAnnuel, $datePremierPaiement);
            
            // 6. Notification à l'utilisateur
            // ...

            return redirect()
                ->route('admin.demandes.index') 
                ->with('success', 'La demande N° ' . $demande->id . ' a été approuvée et l\'échéancier généré !');
        
        } catch (\Exception $e) {
            // Gérer les erreurs de DB ou de logique métier
            // En production, il faudrait loguer l'erreur ($e)
            return back()->with('error', 'Erreur lors de l\'approbation ou de la génération de l\'échéancier.');
        }
    }
    
    // La méthode creerEcheancierRemboursement n'est plus nécessaire.
}