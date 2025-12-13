<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RegisterLogin extends Controller
{
    /**
     * Affiche le formulaire d'inscription et connexion
     */
    public function showRegistrationForm()
    {
        return view('Auth.register');
    }

    /**
     * Traite l'inscription utilisateur
     */
    public function register(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'terms' => 'required|accepted',
        ], 
        [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'terms.required' => 'Vous devez accepter les termes et conditions.',
            'terms.accepted' => 'Vous devez accepter les termes et conditions.',
            'image_path.image' => 'Le fichier doit être une image.',
            'image_path.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        try {
            // Gestion du téléchargement de l'image
            $imagePath = null;
            if ($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('profile_images', 'public');
            }

            // Création de l'utilisateur avec le rôle 'user' par défaut
            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'image_path' => $imagePath,
                'role' => 'user', // Rôle par défaut : user
            ]);

            // Vérifier si l'utilisateur a bien été créé
            if (!$user) {
                return back()->with('error', 'Erreur lors de la création de l\'utilisateur.');
            }

            // Connexion automatique après inscription
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Inscription réussie ! Bienvenue.');
        } catch (\Exception $e) {
            Log::error('Erreur inscription: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Traite la connexion utilisateur
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            
            /** @var User $user */
            $user = Auth::user();
            
            // Redirection selon le rôle
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Connexion administrateur réussie !');
            }
            
            return redirect()->route('users.dashboard')->with('success', 'Connexion réussie !');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email ou mot de passe incorrect.');
    }

    /**
     * Déconnexion utilisateur
     */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }

    
}