<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Se revierte: el servicio restringido se define ÚNICAMENTE en el usuario
     * (users.servicio_id, vía /usuarios/edit -> "Control de Acceso por Servicio"),
     * no en el perfil/rol. Esta columna y su UI en /perfiles quedan eliminadas.
     */
    public function up(): void
    {
        if (Schema::hasColumn('usuario_perfils', 'servicio_id')) {
            Schema::table('usuario_perfils', function (Blueprint $table) {
                $table->dropForeign(['servicio_id']);
                $table->dropColumn('servicio_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('usuario_perfils', 'servicio_id')) {
            Schema::table('usuario_perfils', function (Blueprint $table) {
                $table->foreignId('servicio_id')->nullable()->after('perfil')
                    ->constrained('servicios')->nullOnDelete();
            });
        }
    }
};
