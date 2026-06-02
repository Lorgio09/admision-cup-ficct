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
    Schema::create('gestiones', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Ej: "I-2026", "II-2026"
        $table->boolean('is_active')->default(false); // Solo uno estará en true
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestiones');
    }
};
