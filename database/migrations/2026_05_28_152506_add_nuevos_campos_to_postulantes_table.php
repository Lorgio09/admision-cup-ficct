<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->string('ciudad_nacimiento')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('colegio_procedencia')->nullable();
            $table->string('ciudad_residencia')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropColumn(['ciudad_nacimiento', 'fecha_nacimiento', 'colegio_procedencia', 'ciudad_residencia']);
        });
    }
};