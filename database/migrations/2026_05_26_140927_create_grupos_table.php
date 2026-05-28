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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: SA, SB
            $table->string('turno');  // Ej: Mañana, Tarde, Noche
        
            // Añadimos esta relación con las Aulas
            $table->foreignId('aula_id')->nullable()->constrained()->onDelete('set null');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
