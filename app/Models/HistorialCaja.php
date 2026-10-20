<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistorialCaja extends Model
{
    protected $table = 'historial_cajas';
    protected $guarded = [];

    protected $casts = ['anulado' => 'boolean'];

    public function cajaQuirurgica()
    {
        return $this->belongsTo(CajaQuirurgica::class, 'caja_quirurgicas_id')->withTrashed();
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    // Usuario del sistema que hizo el movimiento
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Movimientos que forman la línea de tiempo (sin retrocesos, desusos ni anulados)
    public function scopeEnLinea($query)
    {
        return $query->whereIn('accion', ['alta', 'avance', 'restauracion'])
                     ->where('anulado', false);
    }

    // Nombre de quien hizo el movimiento
    public function getResponsableAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        if ($this->empleado) {
            return trim(($this->empleado->nombre ?? '') . ' ' . ($this->empleado->apellido ?? ''));
        }
        return 'Sistema';
    }
}