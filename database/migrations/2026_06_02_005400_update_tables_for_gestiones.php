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
        //Agregamos gestion_id a postulantes
        Schema::table('postulantes', function (Blueprint $table) {
            $table->foreignId('gestion_id')->nullable()->constrained('gestiones');
        });

        //Agregamos gestion_id a grupos
        Schema::table('grupos', function (Blueprint $table) {
            $table->foreignId('gestion_id')->nullable()->constrained('gestiones');
        });

        // Eliminamos la columna vieja de cupo en carreras
        Schema::table('carreras', function (Blueprint $table) {
            $table->dropColumn('cupo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
