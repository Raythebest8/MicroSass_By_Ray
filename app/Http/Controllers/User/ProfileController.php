<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User; 

class ProfileController extends Controller
{
    /** Affiche la page de profil avec tous les formulaires. */
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return view('users.profile.index', compact('user'));
    }

    /** Met à jour la photo de profil. */
    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate(['image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        if ($request->hasFile('image_path')) {
            // Suppression de l'ancienne photo
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }

            // Stockage de la nouvelle photo
            $path = $request->file('image_path')->store('profile-images', 'public');
            $user->image_path = $path;
            $user->save();
        }

        return redirect()->route('users.profile.index')->with('status', 'Votre photo de profil a été mise à jour !');
    }
    
    /** Met à jour les informations générales (Nom, Email). */
    public function updateInfo(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('users.profile.index')->with('status', 'Vos informations ont été mises à jour !');
    }

    /** Met à jour le mot de passe. */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('users.profile.index')->with('status', 'Votre mot de passe a été modifié avec succès !');
    }
}