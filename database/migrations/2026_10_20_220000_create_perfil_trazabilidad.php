<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perfil "Trazabilidad": todo menos Pacientes, Camas y Diagnóstico por Imágenes.
        $datos = [
            'admin'            => false,
            'insumos'          => true,
            'estadisticas'     => true,
            'pacientes'        => false,
            'camas'            => false,
            'cirugias'         => true,
            'estudios_medicos' => false,
            'trazabilidad'     => true,
            'updated_at'       => now(),
        ];

        $existe = DB::table('usuario_perfils')->where('perfil', 'Trazabilidad')->exists();

        if ($existe) {
            DB::table('usuario_perfils')->where('perfil', 'Trazabilidad')->update($datos);
        } else {
            DB::table('usuario_perfils')->insert($datos + ['perfil' => 'Trazabilidad', 'created_at' => now()]);
        }
    }

    public function down(): void
    {
        DB::table('usuario_perfils')->where('perfil', 'Trazabilidad')->delete();
    }
};
