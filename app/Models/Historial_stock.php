<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial_stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_id',
        'estudio_medico_id',
        'cirugia_id',
        'cantidad',
        'fecha',
        'empleado_id',
        'paciente_id',
        'comentario',
        'creado_por'
    ];

    public function get_stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }

    public function get_paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id', 'id');
    }

    public function get_empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
    public function get_creador()
    {
        return $this->belongsTo(User::class, 'creado_por', 'id');
    }

    public function get_estudio_medico()
    {
        return $this->belongsTo(EstudioMedico::class, 'estudio_medico_id', 'id');
    }

    // Cirugía que generó este movimiento de stock, si aplica
    public function get_cirugia()
    {
        return $this->belongsTo(Cirugia::class, 'cirugia_id', 'id');
    }
}
