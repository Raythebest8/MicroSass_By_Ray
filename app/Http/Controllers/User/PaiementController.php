<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\Paiement; 
use App\Models\Echeance; 
use App\Models\Entreprise; 
use App\Models\Particulier; 
use Carbon\Carbon;

class PaiementController extends Controller
{
    // ... [index() existante pour l'historique] ...
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $paiements = Paiement::where('user_id', Auth::id())
                            ->with(['echeance' => function ($query) {
                                $query->with('demande'); 
                            }])
                            ->orderByDesc('date_paiement')
                            ->get();

        $viewType = 'Global'; 

        return view('users.paiements.index', [
            'paiements' => $paiements,
            'type' => $viewType, 
        ]);
    }
    
    /**
     * Affiche le formulaire de choix du prêt et du montant.
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        
        // 1. Récupérer les prêts Entreprise actifs
        $demandesEntreprise = Entreprise::where('user_id', $userId)
            ->where('statut', 'actif') // Assurez-vous que 'actif' est la bonne valeur de statut
            ->get()
            ->map(function ($demande) {
                $demande->type = 'entreprise';
                $demande->libelle = $demande->motif . ' (Prêt Entreprise)'; // Crée un libellé clair pour la vue
                return $demande;
            });

        // 2. Récupérer les prêts Particulier actifs
        $demandesParticulier = Particulier::where('user_id', $userId)
            ->where('statut', 'actif') // Assurez-vous que 'actif' est la bonne valeur de statut
            ->get()
            ->map(function ($demande) {
                $demande->type = 'particulier';
                $demande->libelle = $demande->motif . ' (Prêt Particulier)'; // Crée un libellé clair
                return $demande;
            });

        // 3. Combiner et trier
        $demandesActives = $demandesEntreprise->merge($demandesParticulier)->sortBy('libelle');
        
        return view('users.paiement.checkout', compact('demandesActives'));
    }

    /**
     * Gère l'envoi du formulaire de paiement et simule l'initiation de la transaction en ligne.
     * En cas de succès simulé, appelle la logique d'enregistrement final.
     */
    public function process(Request $request)
    {
        // Validation des données du formulaire de paiement en ligne
        $validated = $request->validate([
            'demande_id' => 'required|integer',
            // Le type sera passé en hidden field ou généré côté client/serveur, 
            // ici on le valide s'il est envoyé :
            'type' => 'required|in:entreprise,particulier', 
            'montant' => 'required|integer|min:1000', // Adapté au champ 'montant' du formulaire
            'methode' => 'required|string|max:50',   // Adapté au champ 'methode' du formulaire
        ]);

        $data = [
            'demande_id' => $validated['demande_id'],
            'type' => $validated['type'],
            // Renommer pour correspondre à la logique de la méthode d'enregistrement
            'montant_paiement' => $validated['montant'], 
            'methode_paiement' => $validated['methode'], 
        ];

        // --- SIMULATION DE LA PASSERELLE DE PAIEMENT ---
        
        // Dans un environnement réel, vous feriez ici l'appel à l'API de paiement Mobile Money.
        // Si l'API retourne un succès immédiat ou lance un push USSD :
        
        try {
            // Puisque nous ne pouvons pas appeler une vraie API, nous simulerons le succès
            // en appelant directement la logique d'enregistrement (qui était votre ancienne méthode store)
            $echeancesCouvertes = $this->_recordPayment($data);

            // Redirection après succès simulé
            return redirect()
                ->route('users.paiements.index')
                ->with('success', 'Paiement en ligne de ' . number_format($data['montant_paiement'], 0, ',', ' ') . ' FCFA effectué avec succès via ' . $data['methode_paiement'] . '. ' . $echeancesCouvertes . ' échéance(s) mise(s) à jour.');

        } catch (\Exception $e) {
            // En cas d'échec du paiement (solde insuffisant, etc. géré par _recordPayment)
            return back()->with('error', 'Échec du traitement du paiement : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Logique d'enregistrement final du paiement et de mise à jour des échéances.
     * Prend les données validées en argument (était votre ancienne méthode store).
     */
    private function _recordPayment(array $data)
    {
        $userId = Auth::id();
        $montantPaye = $data['montant_paiement'];

        $model = ($data['type'] === 'entreprise') ? Entreprise::class : Particulier::class;
        $demande = $model::where('user_id', $userId)->find($data['demande_id']);

        if (!$demande) {
            throw new \Exception('Demande de prêt introuvable.');
        }

        $echeancesAPayer = $demande->echeances()
                                    ->where('statut', '!=', 'payée')
                                    ->orderBy('date_prevue', 'asc')
                                    ->get();

        if ($echeancesAPayer->isEmpty()) {
            throw new \Exception('Il n\'y a aucune échéance en attente de paiement pour ce prêt.');
        }
        
        try {
            DB::beginTransaction();

            $resteAPayer = $montantPaye;
            $echeancesCouvertes = 0;
            $referenceTransaction = 'ONLINE-TX-' . strtoupper(uniqid()) . '-' . $demande->id; 

            // Votre logique de couverture des échéances
            foreach ($echeancesAPayer as $echeance) {
                if ($resteAPayer <= 0) {
                    break; 
                }

                $montantEcheance = $echeance->montant_total;
                
                // Votre règle de paiement minimum
                if ($echeancesCouvertes === 0 && $montantPaye < $echeancesAPayer->first()->montant_total) {
                     DB::rollBack();
                     throw new \Exception('Le montant soumis est insuffisant pour couvrir la prochaine échéance complète ('. number_format($echeancesAPayer->first()->montant_total, 0, ',', ' ') . ' FCFA).');
                }

                if ($resteAPayer >= $montantEcheance) {
                    $echeance->update(['statut' => 'payée']);
                    $resteAPayer -= $montantEcheance;
                    $echeancesCouvertes++;
                } else {
                    break;
                }
            }
            
            if ($echeancesCouvertes === 0) {
                 DB::rollBack();
                 throw new \Exception('Le paiement n\'a pas pu être associé à une échéance. Vérifiez le montant.');
            }

            // Enregistrement du paiement
            Paiement::create([
                'echeance_id' => $echeancesAPayer->first()->id, 
                'user_id' => $userId,
                'date_paiement' => Carbon::now(),
                'montant' => $montantPaye,
                'methode_paiement' => $data['methode_paiement'],
                'reference_transaction' => $referenceTransaction,
                'statut' => 'effectué',
            ]);

            DB::commit();

            return $echeancesCouvertes; // Retourne le nombre d'échéances couvertes
            
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $e;
        }
    }
}