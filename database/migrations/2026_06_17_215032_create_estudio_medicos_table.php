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
        Schema::create('estudio_medicos', function (Blueprint $table) {
            $table->id();

            // Claves foráneas (Relaciones)
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('medico_solicitante_id')->constrained('empleados')->cascadeOnDelete();

            // Ámbito del paciente: Interno (I) o Ambulatorio (A)
            $table->string('ia', 20)->nullable();

            // Campos de datos del estudio (Rayos / Tomografías / etc.)
            $table->string('tipo_estudio', 150); // Este mapea conceptualmente a tu "Estudio Real"
            $table->string('regiones')->nullable(); // Regiones anatómicas estudiadas
            $table->date('fecha');

            // Control de insumos y medios de contraste consumidos
            $table->integer('cont_50ml')->default(0);
            $table->integer('cont_100ml')->default(0);
            $table->integer('jeringa_prellenada')->default(0);
            $table->string('descartables')->nullable();
            $table->string('otros_agujas')->nullable(); // Otros insumos y agujas de punción

            // Resultados u observaciones clínicas
            $table->text('resultado')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudio_medicos');
    }
};