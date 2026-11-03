<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * Registra consumos de insumos sobre lotes "demo" manteniendo el historial coherente:
 * el movimiento negativo se agrega y se suma la misma cantidad a la "Carga inicial"
 * del lote, de modo que cantidad_act siempre coincide con la suma del historial.
 */
trait ConsumeStock
{
    protected function consumir(int $stockId, int $cantidad, string $fecha, array $extra): void
    {
        if ($cantidad <= 0) {
            return;
        }

        DB::table('historial_stocks')->insert($extra + [
            'stock_id'    => $stockId,
            'cantidad'    => -$cantidad,
            'fecha'       => $fecha,
            'created_at'  => $fecha,
            'updated_at'  => $fecha,
        ]);

        DB::table('historial_stocks')
            ->where('stock_id', $stockId)
            ->where('comentario', 'Carga inicial')
            ->increment('cantidad', $cantidad);
    }
}
