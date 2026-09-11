<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Umbrales definidos por el médico/encargado al cargar cada insumo.
            // Default 50/30 para mantener el comportamiento actual en insumos ya existentes.
            $table->unsignedInteger('umbral_aviso')->nullable()->default(50)->after('cantidad_act');
            $table->unsignedInteger('umbral_critico')->nullable()->default(30)->after('umbral_aviso');
        });
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['umbral_aviso', 'umbral_critico']);
        });
    }
};
