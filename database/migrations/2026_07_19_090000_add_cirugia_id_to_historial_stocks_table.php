<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Antes, el vínculo entre un movimiento de stock y la cirugía que lo generó
     * era el TEXTO del comentario ("cirugia {id}"), sin ninguna relación real.
     * Eso es frágil: si alguien edita el comentario a mano, se pierde el vínculo
     * sin ningún aviso, y no se puede indexar/consultar de forma confiable.
     * Se agrega cirugia_id como FK real (mismo patrón que estudio_medico_id).
     */
    public function up(): void
    {
        Schema::table('historial_stocks', function (Blueprint $table) {
            $table->foreignId('cirugia_id')->nullable()->after('estudio_medico_id')
                ->constrained('cirugias')->nullOnDelete();
        });

        // Backfill: migramos los movimientos viejos (comentario = "cirugia {id}")
        // a la FK real, sin tocar el texto del comentario para no perder el
        // historial legible ya existente.
        $filas = DB::table('historial_stocks')
            ->where('comentario', 'like', 'cirugia %')
            ->get(['id', 'comentario']);

        foreach ($filas as $fila) {
            if (preg_match('/^cirugia\s+(\d+)$/i', trim($fila->comentario), $m)) {
                $cirugiaId = (int) $m[1];
                $existe = DB::table('cirugias')->where('id', $cirugiaId)->exists();
                if ($existe) {
                    DB::table('historial_stocks')->where('id', $fila->id)->update(['cirugia_id' => $cirugiaId]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('historial_stocks', function (Blueprint $table) {
            $table->dropForeign(['cirugia_id']);
            $table->dropColumn('cirugia_id');
        });
    }
};
