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
        Schema::create('postulantes', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->unique();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->char('sexo', 1);
            $table->string('telefono');
            $table->string('direccion');
            // Claves foráneas (1ra y 2da opción)
            $table->foreignId('carrera_primera_opcion_id')->constrained('carreras');
            $table->foreignId('carrera_segunda_opcion_id')->constrained('carreras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulantes');
    }
};
