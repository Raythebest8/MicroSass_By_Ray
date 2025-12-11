<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterLogin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdminDemandeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DemandePretController;
use App\Http\Controllers\User\PaiementController;
use App\Models\Role;
use App\Http\Controllers\Admin\AdminPretController;
use App\Http\Controllers\Admin\NotificationController;

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
    Route::get('/paiements/retards', [App\Http\Controllers\Admin\AdminPretController::class, 'latePaymentsIndex'])->name('paiements.retards');

    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');

Route::post('/demandes/particulier/{demande}/approuver', [AdminDemandeController::class, 'approuverDemande'])
    ->middleware(['auth', 'role:admin']) // Assurez-vous que ce middleware existe
    ->name('admin.demandes.approuver');
});
// routes/web.php


Route::get('/dashboard', [App\Http\Controllers\User\UserController::class, 'dashboard'])
     ->middleware(['auth', 'role:user'])
     ->name('dashboard');

Route::middleware(['auth', 'role:user'])->group(function () {
    
    Route::controller(UserController::class)->name('users.')->group(function () {
        // Tableau de bord (Peut être retiré si défini plus haut)
         Route::get('/dashboard', 'dashboard')->name('dashboard'); 
        
        // Pages de simulation et de demande de prêt
        Route::get('/simulation', 'simulation')->name('simulation'); 
        Route::get('/demande', [App\Http\Controllers\User\DemandePretController::class, 'index'])->name('demande.index');

        // LES ROUTES QUI ÉTAIENT EN CAUSE SONT MAINTENANT PROTÉGÉES :
        Route::get('/demande/particulier', [App\Http\Controllers\User\ParticulierController::class, 'formParticulier'])->name('demande.particulier');
        // VOS SOUHETTES : CELLE-CI EST MAINTENANT SÉCURISÉE !
        Route::post('/demande/particulier', [App\Http\Controllers\User\ParticulierController::class, 'submitParticulier'])->name('demande.submitParticulier');
        
        Route::get('/demande/entreprise', [App\Http\Controllers\User\EntrepriseController::class, 'formEntreprise'])->name('demande.entreprise');
        Route::post('/demande/entreprise', [App\Http\Controllers\User\EntrepriseController::class, 'submitEntreprise'])->name('demande.submitEntreprise');

        // Pages liées aux prêts
        Route::get('/pretactif', [App\Http\Controllers\User\DemandePretController::class, 'historique'])->name('pretactif');        
        
        // Informations de l'utilisateur
        Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('profile.index');
        // Pages de paiements
        Route::get('/paiements', [App\Http\Controllers\User\PaiementController::class, 'index'])->name('paiements.index');
        
        // Pages d'information
        Route::get('/conditions-generales', 'conditionsGenerales')->name('conditionsGenerales');
        Route::get('/analytics', 'analytics')->name('analytics');
    });
});

