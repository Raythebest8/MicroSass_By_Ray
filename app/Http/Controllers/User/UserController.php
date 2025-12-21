<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise; 
use App\Models\Particulier;


class UserController extends Controller
{
    public function dashboard()
    {
        // Récupérer le nombre total de demandes (Entreprise et Particulier) de l'utilisateur connecté
        
        $totalDemandes = 0;
        if (Auth::check()) {
            $userId = Auth::id();
            $totalDemandes += Entreprise::where('user_id', $userId)->count();
            if (class_exists(Particulier::class)) {
                $totalDemandes += Particulier::where('user_id', $userId)->count();
            }
        }
        return view('users.dashboard', compact('totalDemandes'));
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

    public function analytics()
    {
        return view('users.analytics');
    }

    
    
}
