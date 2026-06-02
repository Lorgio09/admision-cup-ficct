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
       Schema::table('grupos', function (Blueprint $table) {
            $table->string('dias')->nullable()->after('turno'); // Ej: "Lu-Mi-Vi" o "Ma-Ju"
            $table->time('hora_inicio')->nullable()->after('dias'); // Ej: "07:00:00"
            $table->time('hora_fin')->nullable()->after('hora_inicio'); // Ej: "09:15:00"
        });
    }

    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn(['dias', 'hora_inicio', 'hora_fin']);
        });
    }
};
