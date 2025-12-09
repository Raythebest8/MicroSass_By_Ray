<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Assurez-vous d'importer le modèle User

class Particulier extends Model
{
    protected $fillable = [
        // --- Infos de base et Professionnelles ---
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'nom_employeur',
        'secteur_activite',
        'type_emploi',
        'revenu_mensuel',
        
        // --- Détails du Prêt ---
        'montant_souhaite',
        'duree_mois',
        'motif',
        
        // --- Documents (Chemins d'accès) ---
        'justificatif_id',
        'justificatif_domicile',
        'preuves_revenu',
        'rib',
        
        // --- Liens et Statut (Clés pour le système) ---
        'user_id',            // Clé étrangère pour l'utilisateur qui soumet
        'statut',             // 'en attente', 'validée', 'rejetée'
        'admin_id',           // Clé étrangère pour l'administrateur qui traite
        'date_traitement',    // Date de la décision
    ];
    
    // --- RELATIONS ELOQUENT (CARDINALITÉ) ---

    /**
     * Une demande appartient à l'utilisateur qui l'a soumise.
     */
    public function user(): BelongsTo
    {
        // La clé étrangère par défaut est 'user_id'
        return $this->belongsTo(User::class);
    }

    /**
     * La demande a été traitée (validée ou rejetée) par un administrateur.
     */
    public function processedBy(): BelongsTo
    {
        // La clé étrangère est 'admin_id'
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function echeances()
{
    return $this->morphMany(Echeance::class, 'demande');
}
}