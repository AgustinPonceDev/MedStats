<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'lote',
        'fecha_vencimiento',
        'cantidad_act',
        'servicio_id',
        'umbral_aviso',
        'umbral_critico',
        'creado_por',
        'modificado_por'
    ];

    public function get_medicamento()
    {
        return $this->belongsTo(Medicamento::class,'medicamento_id', 'id');
    }

    public function get_servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
    public function get_historial()
    {
        return $this->hasMany(Historial_stock::class, 'stock_id', 'id');
    }
    public function historial_stock()
    {
        return $this->hasMany(Historial_stock::class, 'stock_id');
    }

    /**
     * Devuelve 'critico', 'aviso' o 'ok' según los umbrales cargados por el
     * médico/encargado para este insumo puntual (con fallback a 30/50 si no se cargaron).
     */
    public function estadoStock(): string
    {
        $critico = $this->umbral_critico ?? 30;
        $aviso = $this->umbral_aviso ?? 50;

        if ($this->cantidad_act < $critico) {
            return 'critico';
        }

        if ($this->cantidad_act < $aviso) {
            return 'aviso';
        }

        return 'ok';
    }
}
