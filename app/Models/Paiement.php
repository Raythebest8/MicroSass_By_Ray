<?php
// App/Models/Paiement.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'echeance_id',
        'user_id',
        'date_paiement',
        'montant',
        'methode_paiement',
        'reference_transaction',
        'statut',
    
    ];
    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    public function echeance()
    {
        return $this->belongsTo(Echeance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
