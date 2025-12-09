<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('echeances', function (Blueprint $table) {
            $table->id();
            
            // Relation polymorphique (Morphs to Entreprise or Particulier)
            $table->morphs('demande'); // Crée demande_id (int) et demande_type (string)

            $table->unsignedSmallInteger('mois_numero');
            $table->date('date_prevue');
            $table->unsignedBigInteger('montant_principal');
            $table->unsignedBigInteger('montant_interet');
            $table->unsignedBigInteger('montant_total');
            $table->string('statut')->default('à payer'); // 'à payer', 'payée', 'retard'
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echeances');
    }
};