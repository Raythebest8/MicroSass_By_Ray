<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserCredentials;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AdminGestionUsersController extends Controller
{
    /**
     * Affiche la liste des utilisateurs.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();

        // Retourne la vue en passant la variable $users
        return view('admin.compte_client.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = ['user', 'manager', 'admin'];
        return view('admin.compte_client.create', compact('roles'));
    }

    /**
     * Valide et stocke un nouvel utilisateur en base de données avec un mot de passe automatique et un rôle.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'situation_matrimonial' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:user,admin,manager',
        ]);

        // 2. Génération du mot de passe (Longueur : 8 caractères)
        $rawPassword = $this->generateRandomPassword(8);

        // 3. Création de l'utilisateur avec le rôle et les informations détaillées
        // NOTE: Assurez-vous que toutes ces colonnes existent dans votre table 'users'
        // et sont dans le tableau $fillable de votre modèle User.
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'profession' => $request->profession,
            'situation_matrimonial' => $request->situation_matrimonial,

            // Si la colonne 'name' est requise par Laravel, utilisez cette ligne :
            // 'name' => $request->prenom . ' ' . $request->nom, 

            'email' => $request->email,
            'password' => Hash::make($rawPassword), // Hashage obligatoire
            'role' => $request->role,
        ]);

        // 4. Envoi du mot de passe à l'utilisateur
        try {
            // Nécessite la classe Mailable 'NewUserCredentials'
            Mail::to($user->email)->send(new NewUserCredentials($user, $rawPassword));
        } catch (\Exception $e) {
            Log::error("Échec de l'envoi de l'email pour l'utilisateur {$user->email}: " . $e->getMessage());
            // Vous pouvez ajouter une notification d'erreur ici si l'email est critique
        }

        // 5. Redirection et message de succès
        return redirect()->route('admin.users.index')
            ->with('success', 'Le compte utilisateur ' . $user->prenom . ' ' . $user->nom . ' (Rôle: ' . $user->role . ') a été créé avec succès. Un email contenant le mot de passe temporaire a été envoyé.');
    }

    /**
     * Génère un mot de passe aléatoire, sécurisé.
     * @param int $length
     * @return string
     */
    private function generateRandomPassword(int $length = 8): string
    {
        // Utilise 8 caractères comme demandé.
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        // return Str::of($chars)->random($length);
        return Str::random($length);
    }

    /**
     * Affiche les détails d'un utilisateur spécifique.
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Laravel injecte automatiquement l'utilisateur grâce au Model Binding
        return view('admin.compte_client.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition de l'utilisateur.
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = ['user', 'manager', 'admin'];
        return view('admin.compte_client.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour les informations de l'utilisateur.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Validation des données pour la mise à jour
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'situation_matrimonial' => 'required|string|max:255',
            // L'email doit être unique SAUF pour l'utilisateur actuel
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:user,admin,manager',
        ]);

        // Mise à jour de l'utilisateur
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'profession' => $request->profession,
            'situation_matrimonial' => $request->situation_matrimonial,
            'email' => $request->email,
            'role' => $request->role,
            // Le mot de passe n'est pas mis à jour ici ; il faudrait une méthode séparée si nécessaire
        ]);

        // Redirection
        return redirect()->route('admin.users.index')
            ->with('success', 'Le compte utilisateur ' . $user->prenom . ' a été mis à jour avec succès.');
    }

    /**
     * Supprime l'utilisateur de la base de données.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $userName = $user->prenom . ' ' . $user->nom;

        // Protection : empêcher la suppression de l'utilisateur connecté (optionnel mais recommandé)
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Le compte utilisateur ' . $userName . ' a été supprimé.');
    }
}
