<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->boolean('estudios_medicos')->nullable()->after('cirugias');
        });

        // Sembramos el perfil "Diagnóstico de Imagen" si todavía no existe,
        // con acceso a Insumos, Estadísticas y Estudios Médicos.
        $existe = DB::table('usuario_perfils')->where('perfil', 'Diagnóstico de Imagen')->exists();

        if (!$existe) {
            DB::table('usuario_perfils')->insert([
                'perfil'           => 'Diagnóstico de Imagen',
                'admin'            => false,
                'insumos'          => true,
                'estadisticas'     => true,
                'pacientes'        => false,
                'camas'            => false,
                'cirugias'         => false,
                'estudios_medicos' => true,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        } else {
            // Si ya existe (creado manualmente), le habilitamos el módulo nuevo.
            DB::table('usuario_perfils')
                ->where('perfil', 'Diagnóstico de Imagen')
                ->update(['estudios_medicos' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->dropColumn('estudios_medicos');
        });
    }
};
