<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCaja extends Model
{
    protected $table = 'historial_cajas';
    protected $guarded = [];

    public function cajaQuirurgica()
    {
        return $this->belongsTo(CajaQuirurgica::class, 'caja_quirurgicas_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}