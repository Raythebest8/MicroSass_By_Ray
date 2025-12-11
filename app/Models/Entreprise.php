<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; 


class Entreprise extends Model
{
    use HasFactory; 

    protected $fillable = [
        'nom_entreprise',
        'forme_juridique',
        'numero_rcm',
        'date_creation',
        'secteur_activite',
        'adresse_siege',
        'contact_email',
        'contact_tel',
        'ca_annuel',
        'resultat_net',
        'capital_social',
        'nombre_employes',
        'dettes_encours',
        'montant_souhaite',
        'duree_mois',
        'motif',
        'garanties_proposees',
        'apport_entreprise',
        
        // Chemins des Fichiers
        'statuts_rcm',
        'bilan_comptes',
        'plan_tresorerie',
        'releves_bancaires',
        'rib_entreprise',
        
        // Champs de Traitement et Clé Étrangère
        'user_id',
        'statut',
        'admin_id',
        'date_traitement',
    ];

    /**
     * Les attributs qui doivent être castés en types natifs.
     */
    protected $casts = [
        'bilan_comptes'     => 'array', // Conversion Array <-> JSON pour la colonne TEXT
        'releves_bancaires' => 'array', // Conversion Array <-> JSON pour la colonne TEXT
        'date_traitement'   => 'datetime',
        'date_creation'     => 'integer',
        
       
    ];
    
    /**
     * Une demande d'entreprise appartient à l'utilisateur qui l'a soumise.
     */
    public function user(): BelongsTo
    {
        // Supposons que le modèle User se trouve dans le même namespace ou est importé
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * La demande a été traitée (validée ou rejetée) par un administrateur.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function echeances()
    {
        return $this->morphMany(Echeance::class, 'demande');
    }
}