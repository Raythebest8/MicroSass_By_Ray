<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();

            // Clé étrangère pour lier la demande à l'utilisateur qui l'a créée
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // --- Étape 1: Informations Légales ---
            $table->string('nom_entreprise');
            $table->enum('forme_juridique', ['sa', 'sarl', 'eurl', 'gics', 'autre']);
            $table->string('numero_rcm', 50)->unique(); 
            $table->integer('date_creation'); 
            $table->string('secteur_activite');
            $table->string('adresse_siege');
            $table->string('contact_email');
            $table->string('contact_tel', 20);
            
            // --- Étape 2: Informations Financières ---
            $table->unsignedBigInteger('ca_annuel'); 
            $table->bigInteger('resultat_net'); // Peut être négatif
            $table->unsignedBigInteger('capital_social');
            $table->unsignedSmallInteger('nombre_employes');
            $table->unsignedBigInteger('dettes_encours')->default(0); 
            
            // --- Étape 3: Détails du Prêt ---
            $table->unsignedBigInteger('montant_souhaite');
            $table->integer('duree_mois');
            $table->text('motif'); 
            $table->string('garanties_proposees')->nullable();
            $table->unsignedBigInteger('apport_entreprise')->default(0);
            
            // --- Étape 4: Documents Justificatifs (CHEMINS) ---
            // CORRECTION CRUCIALE : Ces colonnes doivent être nullable car elles sont insérées APRÈS la création de l'ID.
            $table->string('statuts_rcm')->nullable();
            $table->text('bilan_comptes')->nullable(); 
            $table->string('plan_tresorerie')->nullable();
            $table->text('releves_bancaires')->nullable(); 
            $table->string('rib_entreprise')->nullable();
            
            // --- Statut et Traitement ---
            $table->string('statut')->default('en attente'); 
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamp('date_traitement')->nullable();
            $table->decimal('taux_interet', 5, 2)->nullable();
            $table->decimal('montant_accorde', 15, 2)->nullable();
            $table->text('commentaire_approbation')->nullable();
            $table->text('raison_rejet')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};