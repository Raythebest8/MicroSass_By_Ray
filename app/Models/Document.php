<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    // 1. Nom de la table (facultatif si elle est 'documents', mais bonne pratique)
    protected $table = 'documents';

    // 2. Champs remplissables (les colonnes que vous pouvez insérer via ::create ou update)
    protected $fillable = [
        'particulier_id',
        'entreprise_id',
        'type_document', 
        'nom_afficher',  
        'chemin_stockage', 
        'mime_type',
        // Ajoutez ici d'autres champs de votre migration si nécessaire
    ];

    // 3. RELATIONS INVERSES (BelongsTo)

    /**
     * Obtient la demande Particulier associée à ce document.
     */
    public function particulier(): BelongsTo
    {
        // 'particulier_id' est la clé étrangère utilisée dans la table 'documents'
        return $this->belongsTo(Particulier::class, 'particulier_id');
    }

    /**
     * Obtient la demande Entreprise associée à ce document.
     */
    public function entreprise(): BelongsTo
    {
        // 'entreprise_id' est la clé étrangère utilisée dans la table 'documents'
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }
}