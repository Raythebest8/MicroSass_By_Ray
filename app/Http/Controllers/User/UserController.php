<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function dashboard()
    {
        return view('users.dashboard');
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
