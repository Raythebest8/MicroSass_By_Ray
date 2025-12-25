<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Echeance;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer les échéances polymorphiques appartenant à l'utilisateur
        // On utilise 'whereHas' sur la relation morphTo pour filtrer par user_id
        $echeances = Echeance::whereHasMorph('demande', ['App\Models\Particulier', 'App\Models\Entreprise'], function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $events = [];

        foreach ($echeances as $echeance) {
            // Détermination de la couleur selon le statut
            $couleur = '#F59E0B'; // Orange par défaut
            if ($echeance->statut === 'payée') {
                $couleur = '#10B981'; // Vert
            } elseif ($echeance->date_prevue->isPast()) {
                $couleur = '#EF4444'; // Rouge si la date est dépassée et non payée
            }

            $events[] = [
                'title' => 'Échéance : ' . number_format($echeance->montant_total, 0, ',', ' ') . ' FCFA',
                'start' => $echeance->date_prevue->format('Y-m-d'),
                'color' => $couleur, // <-- Très important pour l'affichage immédiat
                'extendedProps' => [
                    'id'      => $echeance->id,
                    'statut'  => $echeance->statut,
                    'montant' => number_format($echeance->montant_total, 0, ',', ' '),
                    'url'     => route('users.paiements.show', $echeance->id)
                ]
            ];
        }

        return view('users.calendar', compact('events'));
    }
}
