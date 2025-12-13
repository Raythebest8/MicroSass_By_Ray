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
        Schema::table('particuliers', function (Blueprint $table) {
            $table->string('justificatif_id')->nullable()->change();
            $table->string('justificatif_domicile')->nullable()->change();
            $table->string('preuves_revenu')->nullable()->change();
            $table->string('rib')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('particuliers', function (Blueprint $table) {
            //
        });
    }
};
