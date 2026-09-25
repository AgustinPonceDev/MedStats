<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CajaQuirurgica extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // Traemos el historial ordenado por fecha de creación (del más antiguo al más nuevo para el eje horizontal)
    public function historiales()
    {
        return $this->hasMany(HistorialCaja::class, 'caja_quirurgicas_id')
                    ->orderBy('created_at', 'asc');
    }
}
