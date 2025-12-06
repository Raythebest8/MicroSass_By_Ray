<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        return view('users.paiements.index', [
            // 'activeLoans' => $activeLoans, 
        ]);
    }
}
