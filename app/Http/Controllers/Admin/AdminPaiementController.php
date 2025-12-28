<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Echeance;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminPaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Paiement::with('user')->where('statut', 'effectué');

        // Recherche par nom ou prénom de l'utilisateur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        // Filtre par méthode de paiement
        if ($request->filled('methode_paiement')) {
            $query->where('methode_paiement', $request->methode_paiement);
        }
        // Calcul du montant total des paiements filtrés
        $totalMontant = (clone $query)->sum('montant');

        $paiements = $query->latest()->paginate(10)->withQueryString();


        // Statistiques des méthodes de paiement

        $statsMethodes = \App\Models\Paiement::select('methode_paiement', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant) as montant_total'))
            ->where('statut', 'effectué')
            ->groupBy('methode_paiement')
            ->get();

        // Calcul dynamique pour la carte (Peut aussi être filtré si besoin)
        $paiementEnCours = \App\Models\Echeance::where('statut', '!=', 'payé')->sum('montant_total');

        return view('admin.paiements.index', compact('paiements', 'paiementEnCours', 'statsMethodes', 'totalMontant'));
    }

    public function create()
    {
        $users = \App\Models\User::orderBy('nom')->get();
        return view('admin.paiements.create', compact('users'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0',
            'methode' => 'required|string',
            'date_paiement' => 'required|date',
        ]);

        // Génération de la référence automatique
        // Format : PAY - DATE DU JOUR - CHAINE ALÉATOIRE
        $reference = 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));

        // Au lieu de chercher par user_id (qui n'existe pas dans echeances)
        // On récupère simplement la toute première échéance "en attente" de la base
        $echeance = \App\Models\Echeance::where('statut', 'en attente')->first();

        // Si vraiment on n'en trouve aucune, on utilise l'ID 1 par défaut 
        // pour satisfaire la contrainte SQL (si vous n'avez pas mis NULL dans phpMyAdmin)
        $echeanceId = $echeance ? $echeance->id : 1;

        \App\Models\Paiement::create([
            'user_id' => $request->user_id,
            'montant' => $request->montant,
            'reference_transaction' => $reference,
            'methode_paiement' => $request->methode,
            'statut' => 'effectué',
            'echeance_id' => $echeanceId,
            'date_paiement' => $request->date_paiement,

        ]);


        return redirect()->route('admin.paiements.index')
            ->with('success', "Paiement enregistré avec la référence : $reference");
    }

    public function retards()
    {
        // On récupère les échéances dont la date_prevue est passée et non payées
        $echeances = \App\Models\Echeance::where('date_prevue', '<', now())
            ->where('statut', '!=', 'payé')
            ->with('demande') // Charge la relation polymorphique
            ->get();

        return view('admin.paiements.retards', compact('echeances'));
    }

public function downloadRecu($id)
{
    // Récupérer le paiement avec ses relations
    $paiement = \App\Models\Paiement::with(['echeance.demande'])->findOrFail($id);
    $echeance = $paiement->echeance;
    $demande = $echeance->demande;

    // 1. Calcul de l'index (Position de l'échéance)
    $index = $demande->echeances->sortBy('id')->values()->search(function ($item) use ($echeance) {
        return $item->id === $echeance->id;
    }) + 1;

    // 2. Calcul des intérêts et principal
    $taux = $demande->taux_interet ?? 0;
    $nbTotal = $demande->echeances->count();
    $interet = ($demande->montant_accorde * ($taux / 100)) / ($nbTotal ?: 1);
    $principal = $echeance->montant - $interet;

    // 3. Calcul du SOLDE RESTANT réel
    // On prend le montant total dû (capital + intérêts) moins ce qui a déjà été payé
    $totalDu = $demande->echeances->sum('montant');
    $dejaPaye = \App\Models\Paiement::whereHas('echeance', function($q) use ($demande) {
        $q->where('demande_id', $demande->id)->where('demande_type', get_class($demande));
    })->sum('montant');

    $solde_restant = $totalDu - $dejaPaye;

    // 4. Préparation des données pour la vue
    $data = [
        'echeance'      => $echeance,
        'paiement'      => $paiement,
        'reference'     => 'REC-' . date('Ymd') . '-' . str_pad($paiement->id, 5, '0', STR_PAD_LEFT),
        'type'          => (get_class($demande) === 'App\Models\Entreprise') ? 'entreprise' : 'particulier',
        'principal'     => $principal,
        'interet'       => $interet,
        'index'         => $index,
        'solde_restant' => $solde_restant, // Cette variable affichera le montant rouge
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Admin.paiements.recu_pdf', $data);
    
    return $pdf->download('Recu_' . $data['reference'] . '.pdf');
}
}
