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
        Schema::create('caja_quirurgicas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique(); // Ej: CAJ-001
            $table->string('nombre' , 50); // Ej: Caja de Cirugía General
            $table->string('estado_actual')->default('Almacenada'); // Esterilizada, En Uso, Lavado...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_quirurgicas');
    }
};
