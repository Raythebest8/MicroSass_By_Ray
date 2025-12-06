<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterLogin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DemandePretController;
use App\Http\Controllers\User\PaiementController;
use App\Models\Role;

Route::get('/', function () {
    return view('Auth/register' );
});

// Routes pour l'inscription, la connexion et la déconnexion
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterLogin::class, 'showRegistrationForm'])->name('auth.show');
    Route::post('/register', [RegisterLogin::class, 'register'])->name('auth.register');
    Route::post('/login', [RegisterLogin::class, 'login'])->name('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [RegisterLogin::class, 'logout'])->name('auth.logout');
});

// Routes administrateur
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Autres routes admin
});

// Routes utilisateur normal
Route::get('/dashboard', [App\Http\Controllers\User\UserController::class, 'dashboard'])
     ->middleware(['auth', 'role:user'])
     ->name('dashboard');

  
// Groupement des routes sous le contrôleur utilisateur
Route::controller(UserController::class)->name('users.')->group(function () {
    // Tableau de bord
    Route::get('/dashboard', 'dashboard')->name('dashboard'); // Nom complet : '
    
    // Pages de simulation et de demande de prêt
    Route::get('/simulation', 'simulation')->name('simulation'); 
    Route::get('/demande', [App\Http\Controllers\User\DemandePretController::class, 'index'])->name('demande.index'); // Nom complet : 'user.demandePret'
    Route::get('/demande/particulier', [App\Http\Controllers\User\DemandePretController::class, 'formParticulier'])->name('demande.particulier'); // Nom complet : 'user.demandePret.particulier'
    Route::post('/demande/particulier', [App\Http\Controllers\User\DemandePretController::class, 'submitParticulier'])->name('demande.submitParticulier');
    Route::get('/demande/entreprise', [App\Http\Controllers\User\DemandePretController::class, 'formEntreprise'])->name('demande.entreprise'); // Nom complet
    Route::post('/demande/entreprise', [App\Http\Controllers\User\DemandePretController::class, 'submitEntreprise'])->name('demande.submitEntreprise');

    // Pages liées aux prêts
    Route::get('/pretactif', 'pretactif')->name('pretactif'); // Nom complet : 'user.pretactif'
    
    // Informations de l'utilisateur
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile.index'); // Nom complet : 'user.profile.index'
    // Pages de paiements
    Route::get('/paiements', [App\Http\Controllers\User\PaiementController::class, 'index'])->name('paiements.index'); // Nom complet : 'user.paiements'
    
    
    // Pages d'information
    Route::get('/conditions-generales', 'conditionsGenerales')->name('conditionsGenerales'); // Nom complet : 'user.conditionsGenerales'
    Route::get('/analytics', 'analytics')->name('analytics'); // Nom complet : 'user.analytics'
});

