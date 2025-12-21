<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('particuliers', function (Blueprint $table) {
            $table->id();
            
            // 🔑 Lien avec l'utilisateur (Client)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // --- Informations Personnelles 
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('telephone');
            $table->string('adresse');
            $table->string('ville')->nullable();
            $table->string('code_postal', 10)->nullable();
            
            //  Informations Professionnelles 
            $table->string('nom_employeur')->nullable();
            $table->string('secteur_activite')->nullable();
            // CORRECTION 1 : Définir explicitement les valeurs de l'ENUM
            $table->enum('type_emploi', ['CDI', 'CDD', 'Indépendant', 'Autre', 'Fonctionnaire']); 
            $table->unsignedBigInteger('revenu_mensuel'); 
            
            //  Détails du Prêt 
            $table->unsignedBigInteger('montant_souhaite');
            $table->integer('duree_mois');
            $table->text('motif'); 
            
            //  Documents (Chemins d'accès aux fichiers) 
            $table->string('justificatif_id'); 
            $table->string('justificatif_domicile'); 
            $table->string('preuves_revenu'); 
            $table->string('rib'); 
            
            // CORRECTION 2 : AJOUT DES CHAMPS DE GESTION/STATUT
            $table->enum('statut', ['en attente', 'validée', 'rejetée'])->default('en attente');
            $table->foreignId('admin_id')->nullable()->constrained('users'); // L'admin peut être null au début
            $table->timestamp('date_traitement')->nullable(); // Date de traitement, null au début
            $table->decimal('taux_interet', 5, 2)->nullable();
            $table->unsignedBigInteger('montant_accorde')->nullable();
            $table->text('commentaire_approbation')->nullable();
            $table->text('raison_rejet')->nullable();
            
            $table->timestamps();
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('particuliers');
        }
};