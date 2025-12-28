<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'reference', 'type', 'montant', 'user_id', 
        'receiver_id', 'methode_paiement', 'libelle', 'statut'
    ];

    // Le client qui a initié l'action (Dépôt ou Retrait)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Le bénéficiaire en cas de transfert
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}