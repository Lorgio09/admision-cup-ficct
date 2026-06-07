<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            // 1. Eliminamos la columna vieja
            $table->dropColumn('nota');
            
            // 2. Agregamos las 3 columnas nuevas permitiendo decimales y nulos
            $table->decimal('nota1', 5, 2)->nullable();
            $table->decimal('nota2', 5, 2)->nullable();
            $table->decimal('nota3', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            // Si nos arrepentimos, revertimos los cambios
            $table->dropColumn(['nota1', 'nota2', 'nota3']);
            $table->integer('nota')->default(0);
        });
    }
};