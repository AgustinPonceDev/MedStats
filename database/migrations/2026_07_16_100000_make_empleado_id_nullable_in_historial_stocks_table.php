<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * La migración original (2025_05_27_001021_create_historial_stocks_table) define
     * empleado_id como nullable(), pero en el esquema real quedó como NOT NULL sin
     * default — por eso "Carga inicial de stock" (que no tiene empleado asociado)
     * explota con "Field 'empleado_id' doesn't have a default value".
     *
     * Se usa SQL directo (MODIFY COLUMN) en vez de ->change() de Blueprint para no
     * depender del paquete doctrine/dbal, y para no tener que dropear la columna
     * (que tiene foreign key y datos de historial ya cargados).
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE historial_stocks MODIFY empleado_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE historial_stocks MODIFY empleado_id BIGINT UNSIGNED NOT NULL');
    }
};
