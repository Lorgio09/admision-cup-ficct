<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->decimal('promedio', 5, 2)->nullable();
            $table->foreignId('carrera_admitida_id')->nullable()->constrained('carreras')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropForeign(['carrera_admitida_id']);
            $table->dropColumn(['promedio', 'carrera_admitida_id']);
        });
    }
};