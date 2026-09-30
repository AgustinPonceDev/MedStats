<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especialidad_id')->constrained('especialidads')->cascadeOnDelete();
            $table->string('nombre', 150);
            $table->timestamps();

            // Un mismo estudio no puede repetirse dos veces dentro de la misma modalidad
            $table->unique(['especialidad_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
