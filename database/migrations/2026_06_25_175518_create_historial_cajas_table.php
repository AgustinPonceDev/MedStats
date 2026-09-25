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
        Schema::create('historial_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_quirurgicas_id')->constrained('caja_quirurgicas')->onDelete('cascade');
            $table->foreignId('empleado_id')->nullable()->constrained('empleados');
            $table->foreignId('cirugia_id')->nullable()->constrained('cirugias'); // Para saber en qué cirugía se usó
            $table->string('estado_registrado'); // Lavado, Esterilización, Transito, Uso
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cajas');
    }
};
