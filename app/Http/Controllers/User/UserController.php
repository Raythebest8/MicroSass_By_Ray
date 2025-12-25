<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise; 
use App\Models\Particulier;
use App\Models\Paiement;
use App\Models\Echeance;


class UserController extends Controller
{
public function dashboard()
{
    $user = auth()->user();

    // 1. Récupération des données de base
    $entreprises = \App\Models\Entreprise::where('user_id', $user->id)->get();
    $particuliers = \App\Models\Particulier::where('user_id', $user->id)->get();
    $toutesLesDemandes = $entreprises->concat($particuliers);

    // 2. Calcul du Total Accordé avec intérêts (votre formule)
    $totalAccorde = $toutesLesDemandes->where('statut', 'Validée')->sum(function($demande) {
        $montant = $demande->montant_souhaite ?? 0;
        $taux = $demande->taux_interet ?? 0;
        return $montant + ($montant * ($taux / 100));
    });

    // 3. Calcul du Remboursé
    $totalRembourse = \App\Models\Paiement::where('user_id', $user->id)
                        ->where('statut', 'effectué')
                        ->sum('montant');

    // 4. Calcul de la PROCHAINE ÉCHÉANCE (Celle qui causait l'erreur)
    $prochaineEcheance = \App\Models\Echeance::whereHasMorph('demande', 
        [\App\Models\Particulier::class, \App\Models\Entreprise::class], 
        function($query) use ($user) {
            $query->where('user_id', $user->id);
        }
    )->where('statut', '!=', 'payé')->orderBy('date_prevue', 'asc')->first();

    // 5. État soldé
    $estSolder = ($totalAccorde > 0 && ($totalAccorde - $totalRembourse) <= 0);

    // 6. Envoi de TOUTES les variables (Vérifiez bien les noms ici)
    return view('Users.Dashboard', compact(
        'toutesLesDemandes', 
        'totalAccorde', 
        'totalRembourse', 
        'prochaineEcheance', 
        'estSolder'
    ));
}
    public function simulation()
    {
        return view('users.simulation');
    }

    public function pretactif()
    {
        return view('users.pretactif');
    }
    

    public function profile()
    {
        return view('users.profile');
    }

    public function paiements()
    {
        return view('user.paiements.index', [
             
        ]);
    }

    public function conditionsGenerales()
    {
        return view('users.conditions-generales');
    }


    public function calendrier()
    {
        return view('users.calendrier');
    }

    
    
}
