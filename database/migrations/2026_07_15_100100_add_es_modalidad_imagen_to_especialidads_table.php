<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('especialidads', function (Blueprint $table) {
            // Marca qué especialidades son "modalidades" válidas para Diagnóstico por Imágenes
            // (Rayos / Tomografía), sin mezclarse con las especialidades quirúrgicas existentes.
            $table->boolean('es_modalidad_imagen')->default(false)->after('nombre');
        });

        $this->sembrarModalidad('Rayos (X)');
        $this->sembrarModalidad('Tomografía');
    }

    private function sembrarModalidad(string $nombre): void
    {
        $id = DB::table('especialidads')->where('nombre', $nombre)->value('id');

        if ($id) {
            DB::table('especialidads')->where('id', $id)->update(['es_modalidad_imagen' => true]);
        } else {
            DB::table('especialidads')->insert([
                'nombre'              => $nombre,
                'es_modalidad_imagen' => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('especialidads', function (Blueprint $table) {
            $table->dropColumn('es_modalidad_imagen');
        });
    }
};
