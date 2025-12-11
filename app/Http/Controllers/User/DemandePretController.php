<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise; 
use App\Models\Particulier; 
use Illuminate\Database\Eloquent\Collection;

class DemandePretController extends Controller
{
    /**
     * Méthode pour afficher le menu principal des demandes (Formulaires).
     */
    public function index()
    {
        return view('users.demande.index');
    }
    
    /**
     * Récupère toutes les demandes (Entreprise et Particulier) de l'utilisateur connecté
     * pour charger la vue d'historique des prêts (pretactif.blade.php).
     */
    public function historique()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        
        // 1. Récupérer les demandes d'entreprise
        $demandesEntreprise = Entreprise::where('user_id', $userId)->get();
        
        $demandes = $demandesEntreprise; // Initialise avec les entreprises

        // 2. Récupérer les demandes de particulier (si le modèle existe)
        if (class_exists(Particulier::class)) {
             $demandesParticulier = Particulier::where('user_id', $userId)->get();
                                                
             // 3. Fusionner les collections SANS COLLISION DE CLÉS
             
             // Utilisation de CONCAT pour ajouter les éléments sans écraser ceux dont l'ID est identique.
             $demandes = $demandes->concat($demandesParticulier);
             
             /* // Ancien code (cause de l'écrasement des demandes avec le même ID):
             // $demandes = $demandes->merge($demandesParticulier); 
             */
        }

        // 4. Trier par date de soumission (la plus récente d'abord)
        $demandes = $demandes->toBase()->sortByDesc('created_at');
        
        // 5. Afficher la vue d'historique (users.pretactif)
        return view('users.pretactif', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * Affiche les détails d'une demande de prêt spécifique, y compris l'échéancier.
     */
    public function showDetails($type, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Déterminer le modèle à utiliser
        $model = ($type === 'entreprise') ? Entreprise::class : Particulier::class;
        
        try {
            // Récupérer la demande avec l'échéancier et s'assurer que l'utilisateur est bien le propriétaire
            $demande = $model::where('user_id', Auth::id())
                            ->with('echeances') // Charge la relation echeances
                            ->findOrFail($id);

            // Vérification de sécurité supplémentaire (bien que le where('user_id') soit déjà là)
            if ($demande->user_id !== Auth::id()) {
                abort(403);
            }
            
            // Calculer le montant restant dû pour l'affichage 
            // (Somme des montants totaux des échéances non encore payées)
            $montantRestantDu = $demande->echeances()
                                        ->where('statut', '!=', 'payée') // Exclure les statuts 'payée'
                                        ->sum('montant_total');

            return view('users.demande.details', compact('demande', 'type', 'montantRestantDu'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Gérer le cas où l'ID n'existe pas ou n'appartient pas à l'utilisateur
            abort(404, 'Demande de prêt introuvable ou accès refusé.');
        }
    }
}