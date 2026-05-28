<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            // Número del recibo de pago de los 700 Bs
            $table->string('recibo_pago')->nullable();

            // Checkbox para confirmar si cuenta con el certificado (true/false)
            $table->boolean('certificado_bachiller')->default(false);

            // Estado del flujo: pendiente, inscrito, rechazado
            $table->string('estado')->default('pendiente');

            // Relación con la tabla users (se llenará recién cuando el admin lo apruebe)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('postulantes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['recibo_pago', 'certificado_bachiller', 'estado', 'user_id']);
        });
    }
};