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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->foreignId('gestion_id')->constrained('gestiones')->onDelete('cascade'); // Para saber de qué semestre es
            $table->timestamps();

            // Regla de seguridad: En un mismo grupo no puede haber dos profesores dictando la misma materia
            $table->unique(['grupo_id', 'materia_id', 'gestion_id'], 'grupo_materia_gestion_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
