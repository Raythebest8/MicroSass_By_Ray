<?php
// App/Models/Echeance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Pas besoin de BelongsTo, car nous utilisons morphTo (relation polymorphique)

class Echeance extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'demande_type', // <-- CORRIGÉ : Doit être 'demande_type' (convention Laravel pour morphTo)
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

    /**
     * Relation générique vers la demande (Particulier ou Entreprise).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function demande()
    {
        // morphTo() trouve automatiquement les colonnes 'demande_id' et 'demande_type'
        // Si vous avez renommé la colonne 'type_demande' en 'demande_type' via une migration,
        // cette relation fonctionnera pour charger la bonne instance (Entreprise ou Particulier).
        return $this->morphTo(); 
    }
    
    /**
     * Une échéance peut être couverte par un ou plusieurs paiements (bien que généralement un seul).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'echeance_id');
    }
}