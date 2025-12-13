<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Document;
use App\Models\User; 


class Entreprise extends Model
{
    use HasFactory; 
        protected $appends = ['type'];


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
    

    public function getTypeAttribute()
    {
        return 'entreprise';
    }
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

    /**
     * Une demande d'entreprise a plusieurs documents associés.
     */
    public function documents(): HasMany
    {
        // Remplacez Document::class par le nom de votre modèle de document réel (ex: App\Models\Document::class)
        // La clé étrangère doit correspondre à la colonne de votre table 'documents' qui pointe vers la table 'entreprises'.
        // Si la colonne est nommée 'entreprise_id' dans la table 'documents', c'est la bonne configuration.
        return $this->hasMany(Document::class, 'entreprise_id');
        
        // Si vous utilisez une clé générique comme 'demande_id' pour les deux types de demandes :
        // return $this->hasMany(Document::class, 'demande_id')->where('demande_type', 'entreprise');
        // (La méthode ci-dessus est plus complexe, la première est recommandée si vous avez des clés séparées.)
    }
}