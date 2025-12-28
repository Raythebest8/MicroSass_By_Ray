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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Référence unique pour le reçu (ex: TRX-20251228-ABCDE)
            $table->string('reference')->unique();

            // Type d'opération
            $table->enum('type', ['depot', 'retrait', 'transfert']);

            // Montant (15 chiffres au total, 2 après la virgule)
            $table->decimal('montant', 15, 2);

            // Le client principal concerné
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Pour un transfert, on stocke l'ID du destinataire
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')->references('id')->on('users');

            // Métadonnées pour le reçu
            $table->string('methode_paiement')->default('especes'); // especes, virement, etc.
            $table->string('libelle')->nullable(); // Commentaire ou motif
            $table->enum('statut', ['succes', 'en_attente', 'echoue'])->default('succes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
