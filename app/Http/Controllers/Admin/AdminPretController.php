<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Imports nécessaires pour le fonctionnement :
use App\Models\Echeance; 
use App\Models\Entreprise; // Nécessaire si non utilisé dynamiquement
use App\Models\Particulier; // Nécessaire si non utilisé dynamiquement

// Classes de Laravel
use Illuminate\Support\Facades\Auth; // <-- Import manquant
use Illuminate\Support\Facades\Log; // <-- Import manquant
use Illuminate\Validation\Rule; // <-- Import manquant
use Carbon\Carbon; // Utilisé dans latePaymentsIndex()



// Notifications
use App\Notifications\DemandeStatusUpdated; 

class AdminPretController extends Controller
{
    /**
     * Affiche la liste des échéances de paiement ayant le statut 'retard'.
     */
    public function latePaymentsIndex()
    {
        // Récupère toutes les échéances dont le statut est 'retard'
        // Nous chargeons la demande associée pour afficher les détails du prêt (Entreprise ou Particulier)
        $lateEcheances = Echeance::where('statut', 'retard')
                                 ->with('demande') // Charge l'objet demande (polymorphique)
                                 ->orderBy('date_prevue', 'asc') // Tri par date d'échéance
                                 ->get();
                                       
        return view('admin.paiements.retards', [
            'echeances' => $lateEcheances,
            'today' => Carbon::today(),
        ]);
    }

    /**
     * Met à jour le statut d'une demande de prêt et notifie l'utilisateur.
     * @param \Illuminate\Http\Request $request
     * @param int $id L'ID de la demande (Particulier ou Entreprise)
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validation des données de la requête
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['Entreprise', 'Particulier'])], 
            'statut' => ['required', 'string', Rule::in(['validée', 'rejetée', 'en attente', 'en cours'])],
            'montant_accorde' => ['nullable', 'numeric', 'required_if:statut,validée', 'min:0'],
            'commentaire_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        // 2. Détermination du Modèle et récupération de la Demande
        // Note: La classe du modèle doit commencer par une majuscule pour correspondre à "App\Models\X"
        $modelClass = "App\\Models\\{$validated['type']}";
        $demande = $modelClass::findOrFail($id);
        
        $oldStatus = $demande->statut;

        // 3. Mise à jour de la Demande
        $demande->update([
            'statut' => $validated['statut'],
            'admin_id' => Auth::id(), // Enregistrement de l'administrateur
            'date_traitement' => now(),
            'montant_accorde' => $validated['montant_accorde'] ?? $demande->montant_accorde,
            'commentaire_admin' => $validated['commentaire_admin'] ?? null,
        ]);

        // -----------------------------------------------------------------
        // 4. LOGIQUE DE NOTIFICATION DE L'UTILISATEUR (Admin -> User)
        // -----------------------------------------------------------------
        if ($oldStatus !== $demande->statut && in_array($demande->statut, ['validée', 'rejetée'])) {
            
            // 4.A. Déclenchement de la notification via la relation user
            $demande->user->notify(new DemandeStatusUpdated(
                $demande, 
                $validated['type'], 
                $demande->statut
            ));

            // 4.B. Logging de l'action
            Log::info("Notification de mise à jour du statut envoyée à l'utilisateur #{$demande->user->id}. Demande {$validated['type']} #{$demande->id}, Statut: {$demande->statut}.");
        }

        // 5. Redirection ou réponse
        return back()->with('success', "Le statut de la demande {$validated['type']} #{$id} a été mis à jour à '{$demande->statut}'.");
    }
}