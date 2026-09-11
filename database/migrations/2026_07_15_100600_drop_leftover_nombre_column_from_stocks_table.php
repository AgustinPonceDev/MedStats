<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La migración 2025_06_04_194652_add_medicamento_id_to_stocks_table ya debería
     * haber eliminado esta columna al agregar medicamento_id, pero en la base real
     * "nombre" siguió existiendo como NOT NULL (columna sin uso: ninguna vista ni
     * controller la llena, todo usa el medicamento vía medicamento_id). Por eso
     * cualquier alta de stock terminaba en "Column 'nombre' cannot be null".
     */
    public function up(): void
    {
        if (Schema::hasColumn('stocks', 'nombre')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->dropColumn('nombre');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('stocks', 'nombre')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->string('nombre', 100)->nullable()->after('id');
            });
        }
    }
};
