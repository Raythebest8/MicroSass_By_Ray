<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterLogin;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDemandeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DemandePretController;
use App\Http\Controllers\User\PaiementController;
use App\Models\Role;
use App\Http\Controllers\Admin\AdminPretController;
use App\Http\Controllers\Admin\AdminDocumentController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\User\ProfileController;

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
    Route::get('/checkout', [PaiementController::class, 'checkout'])->name('checkout');
    // Traite le formulaire et lance la passerelle de paiement
    Route::post('/process', [PaiementController::class, 'process'])->name('process');
    Route::get('/paiements/retards', [App\Http\Controllers\Admin\AdminPretController::class, 'latePaymentsIndex'])->name('paiements.retards');

    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');

    Route::get('/demandes', [AdminDemandeController::class, 'index'])->name('admin.demandes.index');

// Route pour afficher les détails d'une demande
Route::get('/demandes/{type}/{demandeId}/details', [AdminDemandeController::class, 'show'])->name('admin.demandes.details');
Route::get('/documents/{documentId}/download', [AdminDocumentController::class, 'download'])
    ->name('admin.documents.download');

// ... (vos routes approuver et rejeter)
Route::post('demandes/{type}/{demandeId}/approuver', [AdminDemandeController::class, 'approuverDemande'])->name('admin.demande.approuver');
Route::post('demandes/{type}/{demandeId}/rejeter', [AdminDemandeController::class, 'rejeterDemande'])->name('admin.demande.rejeter');
});

// routes/web.php


Route::get('/dashboard', [App\Http\Controllers\User\UserController::class, 'dashboard'])
     ->middleware(['auth', 'role:user'])
     ->name('dashboard');

     Route::middleware('auth')->group(function () {
    // La route manquante !
    Route::get('/profile', [ProfileController::class, 'index'])->name('users.profile.index'); 

    // Les autres routes (doivent être là aussi)
    Route::post('/profile/info', [ProfileController::class, 'updateInfo'])->name('users.profile.update.info');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('users.profile.update.password');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('users.profile.update.photo');
});

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
        
        // Pages de paiements
        Route::get('/paiements', [App\Http\Controllers\User\PaiementController::class, 'index'])->name('paiements.index');
        
        // Pages d'information
        Route::get('/conditions-generales', 'conditionsGenerales')->name('conditionsGenerales');
        Route::get('/analytics', 'analytics')->name('analytics');
    });
});

