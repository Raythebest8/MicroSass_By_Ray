<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // 1. Colonnes de la relation (Clés étrangères)
            // Assurez-vous que ces colonnes correspondent aux clés utilisées dans vos modèles !
            
            // Clé pour les demandes Particulier
            $table->foreignId('particulier_id')->nullable()->constrained('particuliers')->onDelete('cascade');
            
            // Clé pour les demandes Entreprise
            $table->foreignId('entreprise_id')->nullable()->constrained('entreprises')->onDelete('cascade');
            
            // 2. Informations sur le fichier
            $table->string('type_document'); // Ex: 'piece_identite', 'justificatif_domicile', 'etat_financier'
            $table->string('nom_afficher');  // Nom que l'utilisateur voit (pour le téléchargement)
            $table->string('chemin_stockage'); // Chemin réel du fichier sur le disque (Local, S3 ou Google Drive)
            $table->string('mime_type')->nullable(); // Type de fichier (ex: application/pdf)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};