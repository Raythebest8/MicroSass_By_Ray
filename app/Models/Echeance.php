<?php
// App/Models/Echeance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Echeance extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'type_demande', // 'entreprise' ou 'particulier'
        'mois_numero',
        'date_prevue',
        'montant_principal',
        'montant_interet',
        'montant_total',
        'statut', // 'à payer', 'payée', 'retard'
    ];

    protected $casts = [
        'date_prevue' => 'date',
    ];

    // Relation générique vers la demande (Particulier ou Entreprise)
    public function demande()
    {
        return $this->morphTo(); // Relation polymorphique
    }
    
    // Relation One-to-Many vers les paiements (si un paiement couvre plusieurs échéances ou vice-versa)
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}