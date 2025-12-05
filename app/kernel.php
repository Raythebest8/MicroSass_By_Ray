<?php
namespace App\Http;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateWithBasicAuth;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\Role;
class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        
        // Ajoutez votre alias de rôle ici :
        'role' => \App\Http\Middleware\CheckRole::class, // <-- Ceci est CORRECT
            
    ];
}