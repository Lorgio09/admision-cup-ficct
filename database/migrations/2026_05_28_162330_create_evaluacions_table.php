<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            
            // Relación con el postulante
            $table->foreignId('postulante_id')->constrained('postulantes')->onDelete('cascade');
            
            // Relación con la materia (Computación, Matemáticas, etc.)
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            
            // La nota (Regla de negocio: entre 0 y 100)
            $table->integer('nota')->default(0);
            
            $table->timestamps();

            // Evitamos que un postulante tenga dos notas de la misma materia
            $table->unique(['postulante_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};