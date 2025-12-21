<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Particulier;
use App\Models\Entreprise;
use App\Models\User;
use App\Notifications\DemandeStatusUpdated;
use App\Services\AmortizationService;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator; // Classe de pagination manuelle
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserCredentials;


class AdminDemandeController extends Controller
{
    
    // Changement Majeur ici : On charge les deux modèles distincts
    /**
     * Affiche la liste fusionnée et paginée des demandes (Particulier et Entreprise).
     */
    public function index()
    {
        // Initialiser une collection vide pour garantir que $demandesCollection est définie
        $demandesCollection = new Collection();

        try {
            // 1. Récupération et typage des demandes Particulier
            $demandesParticulier = Particulier::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            // Injection de la propriété 'type'
            $demandesParticulier->each(fn($d) => $d->type = 'particulier');

            // 2. Récupération et typage des demandes Entreprise
            $demandesEntreprise = Entreprise::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            // Injection de la propriété 'type'
            $demandesEntreprise->each(fn($d) => $d->type = 'entreprise');

            // 3. Fusion et tri de la Collection NON paginée
            $demandesCollection = $demandesParticulier
                ->concat($demandesEntreprise)
                ->sortByDesc('created_at')
                ->values(); // Réinitialise les clés numériques après le tri

        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération des demandes: " . $e->getMessage());
            // $demandesCollection reste vide si une erreur survient
        }

        // 4. LOGIQUE DE PAGINATION MANUELLE

        $perPage = 15; // Définissez votre nombre d'éléments par page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;

        // Obtenir la "tranche" d'éléments pour la page actuelle
        // NOTE: all() convertit la Collection en Array, mais slice fonctionne sur la Collection
        $itemsForCurrentPage = $demandesCollection->slice($offset, $perPage)->all();

        // Créer l'objet Paginator
        $demandes = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $demandesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // 5. Passer la variable à la vue
        return view('admin.demandes.index', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * Approuve la demande (nous devons maintenant vérifier si c'est Particulier ou Entreprise)
     * * L'injection de modèle doit être remplacée par l'ID + le TYPE.
     */
    /**
     * Approuve la demande, génère l'échéancier, et notifie l'utilisateur.
     * @param Demande $demande - Route Model Binding
     */
    /**
     * Approuve la demande, génère l'échéancier, et notifie l'utilisateur.
     * REMPLACER Demande $demande PAR $type et $demandeId
     */
    public function approuverDemande(Request $request, AmortizationService $amortizationService, string $type, int $demandeId)
    {
        // 1. Détermination du Modèle (Copie de la logique de show())
        if ($type === 'entreprise') {
            $model = Entreprise::class;
        } elseif ($type === 'particulier') {
            $model = Particulier::class;
        } else {
            return back()->with('error', 'Type de demande invalide pour l\'approbation.');
        }

        // Charger la demande (Particulier ou Entreprise)
        $demande = $model::with('user')->findOrFail($demandeId);

        // Reste de votre code (Inchangé)

        // 2. Vérification des permissions
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        // 3. Vérification du statut actuel
        $currentStatus = strtolower($demande->statut);
        if ($currentStatus !== 'en attente' && $currentStatus !== 'en cours d\'examen') {
            return back()->with('error', 'Le statut actuel de la demande ne permet pas cette action.');
        }

        // 4. Validation des données d'approbation (du formulaire modal)
        $request->validate([
            'taux_interet' => 'required|numeric|min:0.01|max:100',
            'duree_mois' => 'required|integer|min:1',
            'montant_accorde' => 'required|numeric|min:1000',
            'commentaire_approbation' => 'nullable|string|max:500', // Ajouté pour le champ optionnel
        ]);

        $tauxAnnuel = $request->taux_interet;
        $dureeMois = $request->duree_mois;
        $montantAccorde = $request->montant_accorde;
        $datePremierPaiement = Carbon::now()->addMonth()->startOfDay();

        try {
            // 5. Mise à jour des données et du statut
            $demande->update([
                'statut' => 'validée',
                'admin_id' => Auth::id(),
                'date_traitement' => now(),
                'taux_interet' => $tauxAnnuel,
                'duree_mois' => $dureeMois,
                'montant_accorde' => $montantAccorde,
                // Enregistrement du commentaire si besoin
                'commentaire_approbation' => $request->commentaire_approbation,
            ]);

            // 6. Génération du tableau d'amortissement
            $demande->type = $type; // Ajouté pour la notification
            $amortizationService->generate($demande, $tauxAnnuel, $dureeMois, $montantAccorde, $datePremierPaiement);

            // 7. Notification à l'utilisateur (validée)
            if ($demande->user) {
                $demande->user->notify(new DemandeStatusUpdated($demande, 'validée', $type));
            }

            // 8. Redirection
            return Redirect::route('admin.demandes.index')
                ->with('success', 'La demande N° ' . $demande->id . ' a été approuvée, l\'échéancier généré et l\'utilisateur notifié !');
        } catch (\Exception $e) {
            Log::error("Erreur d'approbation demande #{$demande->id} (Type: {$type}): " . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'approbation ou de la génération de l\'échéancier.');
        }  
    }

    /**
     * Rejette la demande et notifie l'utilisateur.
     * @param Demande $demande - Route Model Binding
     */

 public function rejeterDemande(Request $request, string $type, int $demandeId)
{
    // 1. Validation (on met min:1 pour être sûr que ça ne bloque pas)
    $request->validate([
        'raison_rejet' => 'required|string|min:1',
    ]);

    // 2. Détermination du modèle
    $model = ($type === 'entreprise') ? Entreprise::class : Particulier::class;
    $demande = $model::findOrFail($demandeId);

    try {
        // 3. Mise à jour
        $success = $demande->update([
            'statut' => 'rejetée',
            'admin_id' => Auth::id(),
            'date_traitement' => now(),
            'raison_rejet' => $request->raison_rejet,
        ]);

        // 4. Notification (on l'entoure d'un try-catch pour qu'elle ne bloque pas le rejet)
        try {
            if ($demande->user) {
                $demande->user->notify(new DemandeStatusUpdated($demande, 'rejetée', $type));
            }
        } catch (\Exception $e) {
            Log::warning("Notification échec : " . $e->getMessage());
        }

        return Redirect::route('admin.demandes.index')
            ->with('warning', 'La demande N° ' . $demande->id . ' a été rejetée.');

    } catch (\Exception $e) {
        // En cas d'erreur SQL (par exemple si la colonne n'existe pas)
        Log::error("Erreur SQL rejet : " . $e->getMessage());
        return back()->with('error', 'Erreur technique : ' . $e->getMessage());
    }
}
    /**
     * Affiche les détails d'une demande de prêt spécifique (Particulier ou Entreprise).
     * * @param string $type ('particulier' ou 'entreprise')
     * @param int $demandeId L'ID de la demande spécifique dans sa table.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $type, int $demandeId)
    {
        // 1. Déterminer le modèle à utiliser
        if ($type === 'entreprise') {
            $model = Entreprise::class;
        } elseif ($type === 'particulier') {
            $model = Particulier::class;
        } else {
            // Gérer le cas où le type est invalide (via l'URL)
            return redirect()->route('admin.demandes.index')->with('error', 'Type de demande invalide.');
        }

        // 2. Récupérer la demande avec l'Eager Loading pour l'utilisateur
        // Utilisez findOrFail pour déclencher une 404 si l'ID n'existe pas
        // $demande = $model::with('user')->findOrFail($demandeId);
        $demande = $model::with(['user', 'documents'])->findOrFail($demandeId);

        // Optionnel : Récupérer d'autres données liées (ex: documents, historique)
        // $documents = $demande->documents;

        // 3. Afficher la vue
        return view('admin.demandes.details', [
            'demande' => $demande,
            'type' => $type, // Utile pour les formulaires ou l'affichage
        ]);
    }
}
