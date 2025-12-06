<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DemandePretController extends Controller
{
    public function index()
    {
        return view('users.demande.index', [
            // 'activeLoans' => $activeLoans, 
        ]);
    }
    

    public function formParticulier()
    {
        return view('users.demande.particulier', [
            // 'activeLoans' => $activeLoans, 
        ]);
    }   

    public function formEntreprise()
    {
        return view('users.demande.entreprise', [
            // 'activeLoans' => $activeLoans, 
        ]);
    }

    public function submitParticulier(Request $request)
    {
        // Logique pour traiter le formulaire de demande de prêt pour un particulier
    }

    public function submitEntreprise(Request $request)
    {
        // Logique pour traiter le formulaire de demande de prêt pour une entreprise
    }


}