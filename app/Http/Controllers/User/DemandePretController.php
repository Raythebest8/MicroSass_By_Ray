<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise; // Assurez-vous que ce modèle existe
use App\Models\Particulier; // Créez ce modèle si ce n'est pas fait

class DemandePretController extends Controller
{
    // Méthode pour afficher le menu principal des demandes
    public function index()
    {
        return view('users.demande.index');
    }
    
    /**
     * Récupère toutes les demandes (Entreprise et Particulier) de l'utilisateur connecté.
     * Cette méthode chargera la vue d'historique des prêts (pretactif.blade.php).
     */
    public function historique()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // 1. Récupérer les demandes d'entreprise
        $demandesEntreprise = Entreprise::where('user_id', $userId)
                                        ->get();

        // 2. Si le modèle Particulier existe :
        if (class_exists(Particulier::class)) {
             $demandesParticulier = Particulier::where('user_id', $userId)->get();
             // 3. Fusionner les collections
             $demandes = $demandesEntreprise->merge($demandesParticulier);
        } else {
             // Si seul le modèle Entreprise est défini
             $demandes = $demandesEntreprise;
        }

        // 4. Trier par date de soumission (la plus récente d'abord)
        $demandes = $demandes->sortByDesc('created_at');
        
        // 5. Afficher la vue d'historique (users.pretactif)
        return view('users.pretactif', [
            'demandes' => $demandes,
        ]);
    }

    // App/Http/Controllers/user/DemandePretController.php

// ... (dans la classe DemandePretController) ...

/**
 * Affiche les détails d'une demande de prêt spécifique, y compris l'échéancier.
 */
public function showDetails($type, $id)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $model = ($type === 'entreprise') ? Entreprise::class : Particulier::class;
    
    // Récupérer la demande avec l'échéancier
    $demande = $model::where('user_id', Auth::id())
                     ->with('echeances')
                     ->findOrFail($id);

    // Vérification de sécurité supplémentaire
    if ($demande->user_id !== Auth::id()) {
        abort(403);
    }
    
    // Calculer le montant restant dû pour l'affichage (somme des montants totaux 'à payer')
    $montantRestantDu = $demande->echeances()
                                ->where('statut', 'à payer')
                                ->sum('montant_total');

    return view('users.demande.details', compact('demande', 'type', 'montantRestantDu'));
}
}