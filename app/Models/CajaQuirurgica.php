<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CajaQuirurgica extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // La línea de tiempo muestra solo los movimientos vigentes
    public function historiales()
    {
        return $this->hasMany(HistorialCaja::class, 'caja_quirurgicas_id')
                    ->enLinea()
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc');
    }
}