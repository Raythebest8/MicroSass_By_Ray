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
 
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Utilisation de l'Eager Loading pour charger l'échéance et la demande associée
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
     * Gère l'enregistrement d'un nouveau paiement soumis par l'utilisateur.
     * Met à jour le statut des échéances payées.
     */
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire
        $validated = $request->validate([
            'demande_id' => 'required|integer',
            'type' => 'required|in:entreprise,particulier',
            'montant_paiement' => 'required|integer|min:1',
            'methode_paiement' => 'required|string|max:50',
            // Note: Dans un système réel, une passerelle de paiement validerait cette transaction.
        ]);

        $userId = Auth::id();
        $montantPaye = $validated['montant_paiement'];

        // Identifier le modèle de demande (Entreprise ou Particulier)
        $model = ($validated['type'] === 'entreprise') ? Entreprise::class : Particulier::class;

        // 2. Récupérer la demande
        $demande = $model::where('user_id', $userId)->find($validated['demande_id']);

        if (!$demande) {
            return back()->with('error', 'Demande de prêt introuvable.');
        }

        // Récupérer les échéances dues, non payées, triées par date
        $echeancesAPayer = $demande->echeances()
                                  ->where('statut', '!=', 'payée') // Inclut 'à payer' et 'retard'
                                  ->orderBy('date_prevue', 'asc')
                                  ->get();

        if ($echeancesAPayer->isEmpty()) {
            return back()->with('error', 'Il n\'y a aucune échéance en attente de paiement pour ce prêt.');
        }
        
        // 3. Traitement du paiement dans une transaction DB
        try {
            DB::beginTransaction();

            $resteAPayer = $montantPaye;
            $echeancesCouvertes = 0;
            $referenceTransaction = 'TX-' . strtoupper(uniqid()) . '-' . $demande->id; // Référence unique

            // Parcourir les échéances de la plus ancienne à la plus récente
            foreach ($echeancesAPayer as $echeance) {
                if ($resteAPayer <= 0) {
                    break; 
                }

                $montantEcheance = $echeance->montant_total;
                
                // Si le paiement ne couvre pas la première échéance non payée, refuser la transaction (Simplification)
                // Cela force l'utilisateur à payer au moins l'échéance la plus ancienne en totalité.
                if ($echeancesCouvertes === 0 && $montantPaye < $echeancesAPayer->first()->montant_total) {
                     DB::rollBack();
                     return back()->with('error', 'Le montant soumis est insuffisant pour couvrir la prochaine échéance complète ('. number_format($montantEcheance, 0, ',', ' ') . ' FCFA).');
                }

                if ($resteAPayer >= $montantEcheance) {
                    // Le paiement couvre entièrement cette échéance
                    $echeance->update(['statut' => 'payée']);
                    $resteAPayer -= $montantEcheance;
                    $echeancesCouvertes++;
                } else {
                    // Paiement partiel de l'échéance suivante (ici, nous arrêtons de couvrir d'autres échéances)
                    break;
                }
            }
            
            // Si aucune échéance n'a été couverte (cela ne devrait arriver que si la validation min a échoué)
            if ($echeancesCouvertes === 0) {
                 DB::rollBack();
                 return back()->with('error', 'Le paiement n\'a pas pu être associé à une échéance. Vérifiez le montant.');
            }

            // 4. Enregistrement du paiement dans la table `paiements`
            // Nous lions le paiement à la première échéance couverte pour l'historique
            Paiement::create([
                'echeance_id' => $echeancesAPayer->first()->id, 
                'user_id' => $userId,
                'date_paiement' => Carbon::now(),
                'montant' => $montantPaye,
                'methode_paiement' => $validated['methode_paiement'],
                'reference_transaction' => $referenceTransaction,
                'statut' => 'effectué',
            ]);

            DB::commit();

            return redirect()
                ->route('users.demande.details', ['type' => $validated['type'], 'id' => $demande->id])
                ->with('success', 'Paiement de ' . number_format($montantPaye, 0, ',', ' ') . ' FCFA effectué. ' . $echeancesCouvertes . ' échéance(s) mise(s) à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Loguer l'erreur $e
            return back()->with('error', 'Une erreur critique est survenue lors du traitement : ' . $e->getMessage());
        }
    }
}