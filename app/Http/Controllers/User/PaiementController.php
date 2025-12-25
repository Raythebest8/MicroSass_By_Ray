<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Paiement;
use App\Models\Echeance;
use App\Models\Entreprise;
use App\Models\Particulier;
use App\Models\User; // Ajouté
use App\Notifications\PaiementRecuNotification; // Ajouté
use Illuminate\Support\Facades\Notification; // Ajouté
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaiementRecuMail;

class PaiementController extends Controller
{
    /**
     * Affiche l'historique des paiements de l'utilisateur.
     */
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
     * Affiche les détails d'un paiement spécifique.
     */
    public function show($id)
    {
        $paiement = Paiement::with(['echeance.demande'])->findOrFail($id);
        $demande = $paiement->echeance->demande;

        // Calcul du total dû et déjà payé
        $totalDu = $demande->echeances()->sum('montant_total');

        $totalDejaPaye = Paiement::whereHas('echeance', function ($q) use ($demande) {
            $q->where('demande_id', $demande->id)
                ->where('demande_type', get_class($demande));
        })->where('statut', 'effectué')->sum('montant');

        $montantRestant = max(0, $totalDu - $totalDejaPaye);

        // --- SUPPRESSION DU $demande->update() ---
        // On ne modifie plus la demande ici. 
        // On laisse le statut 'validée' tel quel.

        return view('users.paiements.show', compact('paiement', 'montantRestant'));
    }
    /**
     * Affiche le formulaire de choix du prêt et du montant.
     */
   public function checkout()
{
    $userId = Auth::id();

    // Récupérer les prêts Entreprise (On élargit la recherche du statut)
    $demandesEntreprise = Entreprise::where('user_id', $userId)
        ->whereIn('statut', ['Validée', 'validée', 'Validé', 'validé', 'en_cours'])
        ->get()
        ->map(function ($demande) {
            $demande->type = 'entreprise';
            // On s'assure d'avoir un libellé même si le motif est vide
            $name = $demande->nom_entreprise ?? $demande->motif ?? 'Prêt sans motif';
            $demande->libelle = $name . ' (Entreprise)';
            return $demande;
        });

    // Récupérer les prêts Particulier
    $demandesParticulier = Particulier::where('user_id', $userId)
        ->whereIn('statut', ['Validée', 'validée', 'Validé', 'validé', 'en_cours'])
        ->get()
        ->map(function ($demande) {
            $demande->type = 'particulier';
            $demande->libelle = ($demande->motif ?? 'Prêt') . ' (Particulier)';
            return $demande;
        });

    $demandesActives = $demandesEntreprise->merge($demandesParticulier);

    return view('users.paiements.checkout', compact('demandesActives'));
}


    /**
     * Gère l'envoi du formulaire de paiement et simule l'initiation.
     */
   public function process(Request $request)
{
    $request->validate([
        'demande_id' => 'required',
        'montant' => 'required|numeric',
        'type' => 'required',
        'methode' => 'required'
    ]);

    $typeModel = "App\\Models\\" . ucfirst($request->type);
    $demande = $typeModel::findOrFail($request->demande_id);
    

    // On cherche l'échéance en cours
    $echeance = Echeance::where('demande_id', $request->demande_id)
        ->where('demande_type', $typeModel)
        ->where('statut', '!=', 'payé')
        ->first();

    // Si aucune échéance n'existe, on en crée une (cas test ou paiement libre)
    if (!$echeance) {
        $dernierMois = Echeance::where('demande_id', $request->demande_id)
            ->where('demande_type', $typeModel)
            ->max('mois_numero') ?? 0;

        $echeance = new Echeance();
        $echeance->demande_id = $request->demande_id;
        $echeance->demande_type = $typeModel;
        $echeance->date_prevue = now()->addMonth();
        $echeance->statut = 'en attente';
        $echeance->mois_numero = $dernierMois + 1;
        $echeance->montant_total = $request->montant;
        $echeance->montant_principal = $request->montant;
        $echeance->montant_interet = 0;
        $echeance->save();
    }

    try {
        DB::beginTransaction();

        $paiement = new Paiement();
        $paiement->echeance_id = $echeance->id;
        $paiement->user_id = auth()->id();
        $paiement->date_paiement = now();
        $paiement->montant = $request->montant;
        $paiement->methode_paiement = $request->methode;
        $paiement->reference_transaction = 'PAY-' . strtoupper(uniqid());
        $paiement->statut = 'effectué';
        $paiement->save();

        $echeance->update(['statut' => 'payé']);

        DB::commit();
        return redirect()->route('users.paiements.index')->with('success', 'Paiement effectué avec succès !');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', "Erreur : " . $e->getMessage());
    }
}
    /**
     * Logique d'enregistrement et cascade de paiement sur les échéances.
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
            throw new \Exception('Aucune échéance en attente pour ce prêt.');
        }

        try {
            DB::beginTransaction();

            $resteAPayer = $montantPaye;
            $echeancesCouvertes = 0;
            $referenceTransaction = 'ONLINE-TX-' . strtoupper(uniqid()) . '-' . $demande->id;

            foreach ($echeancesAPayer as $echeance) {
                if ($resteAPayer <= 0) break;

                $montantEcheance = $echeance->montant_total;

                // Vérification montant minimum (première itération)
                if ($echeancesCouvertes === 0 && $montantPaye < $echeancesAPayer->first()->montant_total) {
                    throw new \Exception('Le montant est insuffisant pour la première échéance (' . number_format($echeancesAPayer->first()->montant_total, 0, ',', ' ') . ' FCFA).');
                }

                if ($resteAPayer >= $montantEcheance) {
                    $echeance->update(['statut' => 'payée']);
                    $resteAPayer -= $montantEcheance;
                    $echeancesCouvertes++;
                } else {
                    break; // On ne gère pas les paiements partiels ici selon ta logique
                }
            }

            if ($echeancesCouvertes === 0) {
                throw new \Exception('Le paiement n\'a pu couvrir aucune échéance complète.');
            }

            // Création du record de paiement
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
            return $echeancesCouvertes;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Télécharge le reçu de paiement au format PDF.
     */
    public function downloadRecu($id)
    {
        $paiement = Paiement::with(['user', 'echeance.demande'])->findOrFail($id);

        // Vérifier que le paiement appartient bien à l'utilisateur connecté
        if ($paiement->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('Users.paiements.recu', compact('paiement'));

        return $pdf->download('recu-paiement-' . $paiement->reference_transaction . '.pdf');
    }
}
