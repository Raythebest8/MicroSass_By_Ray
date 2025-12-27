<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\Particulier;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // RAPPORTS


    public function rapports(Request $request)
    {
        // Récupérer la date choisie ou utiliser "aujourd'hui" par défaut
        $dateChoisie = $request->input('semaine') ? Carbon::parse($request->input('semaine')) : now();

        // Calculer le début et la fin de LA SEMAINE de cette date
        $debutSemaine = $dateChoisie->copy()->startOfWeek()->startOfDay();
        $finSemaine = $dateChoisie->copy()->endOfWeek()->endOfDay();

        // Volume Entreprises (Filtré par la semaine choisie)
        $volumeEntreprises = \App\Models\Entreprise::whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant_souhaite') ?? 0;

        // Volume Particuliers (Filtré par la semaine choisie)
        $volumeParticuliers = \App\Models\Particulier::whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant_souhaite') ?? 0;

        $volumePrete = $volumeEntreprises + $volumeParticuliers;

        // Autres statistiques filtrées par la même période
        $nouveauxPrets = \App\Models\Entreprise::whereBetween('created_at', [$debutSemaine, $finSemaine])->count()
            + \App\Models\Particulier::whereBetween('created_at', [$debutSemaine, $finSemaine])->count();

        $encaissements = \App\Models\Paiement::whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant') ?? 0;

        $derniersPaiements = \App\Models\Paiement::whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->latest()->take(10)->get();

        return view('admin.rapports.index', compact(
            'nouveauxPrets',
            'volumePrete',
            'volumeEntreprises',
            'volumeParticuliers',
            'encaissements',
            'derniersPaiements',
            'debutSemaine',
            'finSemaine',
            'dateChoisie'
        ));
    }
    // page des transactions (paiements)
    public function transactions()
    {
        return view('admin.transactions.index');
    }

    public function rapportsPrint(Request $request)
    {
        $dateChoisie = $request->input('semaine') ? \Carbon\Carbon::parse($request->input('semaine')) : now();
        $debutSemaine = $dateChoisie->copy()->startOfWeek();
        $finSemaine = $dateChoisie->copy()->endOfWeek();

        // Récupération des données (identique à votre fonction rapports)
        $volumeEntreprises = \App\Models\Entreprise::whereBetween('created_at', [$debutSemaine, $finSemaine])->sum('montant_souhaite') ?? 0;
        $volumeParticuliers = \App\Models\Particulier::whereBetween('created_at', [$debutSemaine, $finSemaine])->sum('montant_souhaite') ?? 0;
        $volumePrete = $volumeEntreprises + $volumeParticuliers;

        $paiements = \App\Models\Paiement::whereBetween('created_at', [$debutSemaine, $finSemaine])->get();
        $encaissements = $paiements->sum('montant');

        return view('admin.rapports.print', compact('volumePrete', 'volumeEntreprises', 'volumeParticuliers', 'encaissements', 'paiements', 'debutSemaine', 'finSemaine'));
    }



    public function pret(Request $request)
    {
        $filter = $request->query('filter', 'tous'); // 'tous' par défaut

        // 1. Récupération de base
        $queryEntreprises = \App\Models\Entreprise::with('echeances.paiements');
        $queryParticuliers = \App\Models\Particulier::with('echeances.paiements');

        // 2. Application des filtres de statut
        if ($filter === 'actifs') {
            $queryEntreprises->where('statut', 'en_cours');
            $queryParticuliers->where('statut', 'en_cours');
        } elseif ($filter === 'termine') {
            $queryEntreprises->where('statut', 'terminé');
            $queryParticuliers->where('statut', 'terminé');
        } else {
            // 'tous' : on prend ceux qui sont validés ou en cours
            $queryEntreprises->whereIn('statut', ['Validée', 'validé', 'en_cours', 'terminé']);
            $queryParticuliers->whereIn('statut', ['Validée', 'validé', 'en_cours', 'terminé']);
        }

        $entreprises = $queryEntreprises->get()->map(fn($item) => $this->formatDemande($item, 'entreprise'));
        $particuliers = $queryParticuliers->get()->map(fn($item) => $this->formatDemande($item, 'particulier'));

        // 3. Fusion et Tri (Retards en premier)
        $demandesActives = $entreprises->concat($particuliers)->sortByDesc('en_retard');

        $entreprises = \App\Models\Entreprise::whereIn('statut', ['Validée', 'validé', 'en_cours'])
            ->with('echeances.paiements') // On charge les paiements via les échéances
            ->get()
            ->map(function ($item) {
                // Calculer le total payé en parcourant les échéances
                $totalPaye = $item->echeances->flatMap->paiements
                    ->where('statut', 'effectué')
                    ->sum('montant');



                return $this->formatDemande($item, 'entreprise', $totalPaye);
            });

        // ... même logique pour les particuliers
        $particuliers = \App\Models\Particulier::whereIn('statut', ['Validée', 'validé', 'en_cours'])
            ->with('echeances.paiements') // On charge les paiements via les échéances
            ->get()
            ->map(function ($item) {
                // Calculer le total payé en parcourant les échéances
                $totalPaye = $item->echeances->flatMap->paiements
                    ->where('statut', 'effectué')
                    ->sum('montant');

                return $this->formatDemande($item, 'particulier', $totalPaye);
            });

        // Fusionner les deux collections
        $demandesActives = $entreprises->concat($particuliers);
        return view('admin.pret.index', compact('demandesActives', 'filter'));
    }

    /**
     * Fonction helper pour calculer les totaux et formater les données
     */
    private function formatDemande($demande, $type)
    {
        // 1. Calcul du total payé via les relations
        $totalPaye = $demande->echeances->flatMap->paiements
            ->where('statut', 'effectué')
            ->sum('montant');

        $montantTotal = $demande->montant_accorde ?? $demande->montant_souhaite ?? 0;
        $restant = max(0, $montantTotal - $totalPaye);

        // Calcul de la date d'échéance
        $prochaine = $demande->echeances
            ->where('statut', 'en attente')
            ->sortBy('date_echeance')
            ->first();

        $dateEcheance = $prochaine ? \Carbon\Carbon::parse($prochaine->date_echeance) : null;

        // Déterminer si le paiement est en retard
        // On considère en retard si la date est passée (avant aujourd'hui) et non payée
        $demande->en_retard = $dateEcheance ? $dateEcheance->isPast() : false;

        // Assignation pour la vue
        $demande->prochaine_echeance = $dateEcheance;
        $demande->mensualite = $prochaine ? $prochaine->montant : ($demande->montant_accorde / 12);

        // 2. Calcul de la mensualité (Correction de la variable $item -> $demande)
        // On utilise le revenu mensuel pour les particuliers ou le CA pour les entreprises
        $revenuReferent = ($type === 'entreprise')
            ? ($demande->chiffre_affaire_mensuel ?? 0)
            : ($demande->revenu_mensuel ?? 0);

        // Si le revenu est renseigné, on prend 33%, sinon on divise le prêt par 12 mois
        $mensualiteCalculee = $revenuReferent > 0
            ? ($revenuReferent * 0.33)
            : ($montantTotal / 12);

        // 3. Gestion de l'affichage du nom
        if ($type === 'entreprise') {
            $demande->display_name = $demande->nom_entreprise ?? 'Entreprise sans nom';
        } else {
            $demande->display_name = $demande->user
                ? ($demande->user->nom . ' ' . $demande->user->prenom)
                : ($demande->nom . ' ' . $demande->prenom);
        }

        // 4. Prochaine échéance
        $prochaine = $demande->echeances
            ->where('statut', 'en attente')
            ->where('date_echeance', '>=', now()->format('Y-m-d'))
            ->sortBy('date_echeance')
            ->first();

        // 5. Assignation des variables pour la vue
        $demande->paye = $totalPaye;
        $demande->restant = $restant;
        $demande->pourcentage = $montantTotal > 0 ? round(($totalPaye / $montantTotal) * 100) : 0;
        $demande->type_label = ($type === 'entreprise') ? 'Entreprise' : 'Particulier';
        $demande->mensualite = $prochaine ? $prochaine->montant : $mensualiteCalculee;
        $demande->prochaine_echeance = $prochaine ? $prochaine->date_echeance : now()->addMonth()->day(5);

        return $demande;
    }

    public function remboursementListe()
    {
        $entreprises = \App\Models\Entreprise::whereIn('statut', ['Validée', 'validé', 'en_cours'])
            ->with('echeances.paiements')
            ->get()
            ->map(fn($item) => $this->formatDemande($item, 'entreprise'));
        $particuliers = \App\Models\Particulier::whereIn('statut', ['Validée', 'validé', 'en_cours'])
            ->with('echeances.paiements')
            ->get()
            ->map(fn($item) => $this->formatDemande($item, 'particulier'));
        // Fusionner les deux collections
        $demandesActives = $entreprises->concat($particuliers)->sortByDesc('en retard');

        return view('admin.remboursement.index', compact('demandesActives'));
    }

    public function remboursementDetail($id, $type)
    {
        // 1. Déterminer le modèle (Entreprise ou Particulier)
        $model = ($type === 'entreprise')
            ? \App\Models\Entreprise::class
            : \App\Models\Particulier::class;

        // 2. Récupérer les données avec les relations
        $demande = $model::with(['echeances.paiements', 'user'])->findOrFail($id);

        // 3. Formater les calculs (payé, restant, etc.) via votre fonction existante
        $demande = $this->formatDemande($demande, $type);

        // 4. Envoyer à la vue avec compact

        $model = ($type === 'entreprise') ? \App\Models\Entreprise::class : \App\Models\Particulier::class;

        // On récupère UN SEUL prêt
        $demande = $model::with(['echeances.paiements', 'user'])->findOrFail($id);
        $demande = $this->formatDemande($demande, $type);

        // On l'envoie à la vue 'detail' (et non 'index')
        return view('admin.remboursement.details', compact('demande'));
    }
}
