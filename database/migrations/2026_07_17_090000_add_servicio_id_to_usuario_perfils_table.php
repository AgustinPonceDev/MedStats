<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Permite atar un servicio directamente al PERFIL (rol), en vez de tener que
     * asignárselo a cada usuario individualmente en /usuarios. Si un usuario tiene
     * su propio servicio_id, ese gana (caso especial); si no, hereda el del perfil.
     */
    public function up(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->foreignId('servicio_id')->nullable()->after('perfil')
                ->constrained('servicios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropColumn('servicio_id');
        });
    }
};
