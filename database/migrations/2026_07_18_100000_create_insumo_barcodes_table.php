<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo de códigos de barra "conocidos": qué medicamento representan, y el
     * último lote/vencimiento/cantidad con que se cargaron (solo de referencia,
     * para autocompletar — no es la fuente de verdad del stock, eso sigue siendo
     * la tabla stocks).
     */
    public function up(): void
    {
        Schema::create('insumo_barcodes', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->cascadeOnDelete();
            $table->string('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('cantidad_referencia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insumo_barcodes');
    }
};
