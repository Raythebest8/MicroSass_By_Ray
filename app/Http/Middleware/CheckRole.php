<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <-- AJOUTER CET IMPORT !

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Modif : le paramètre $role doit être variadique pour supporter plusieurs rôles (voir section 2)
    public function handle(Request $request, Closure $next, string ...$roles): Response 
    {
        // 1. Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) { // Utiliser Auth::check() pour la vérification de connexion
            return redirect()->route('auth.show')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Stocker l'utilisateur pour un accès plus propre
        $user = Auth::user(); 

        // 2. Vérifier si l'utilisateur a le rôle requis (Utilisation de in_array pour supporter plusieurs rôles)
        // La méthode in_array vérifie si la valeur de $user->role est présente dans le tableau $roles.
        if (!in_array($user->role, $roles)) { 
            // Redirection vers une vue d'erreur 403 (Accès Refusé)
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }

  


}