<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'profession',
        'situation_matrimonial',
        'password',
        'image_path',
        'role',
    ];

    /**
     * Les attributs qui doivent être cachés lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est un utilisateur normal
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }


    protected static function boot()
    {
        parent::boot();

        // Événement exécuté AVANT l'enregistrement d'un nouvel utilisateur
        static::creating(function ($user) {

            // Le numéro de compte est généré UNIQUEMENT si le rôle est 'user'
            if ($user->role === 'user' || $user->role === 'client') {
                $user->numero_compte = self::generateUniqueAccountNumber();
            }

            // Si l'utilisateur est un 'admin', 'manager', etc., le champ 'numero_compte' restera NULL
        });
    }

    /**
     * Génère un numéro de compte unique (méthode non modifiée).
     */
    public static function generateUniqueAccountNumber()
    {
        $prefixe = 'MF' . date('y');

        do {
            $randomNumber = mt_rand(10000000, 99999999);
            $accountNumber = $prefixe . $randomNumber;
        } while (self::where('numero_compte', $accountNumber)->exists());

        return $accountNumber;
    }
}
