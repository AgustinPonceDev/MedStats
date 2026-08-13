<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstudioMedico extends Model
{
    use HasFactory;

    protected $table = 'estudio_medicos';

    protected $fillable = [
        'paciente_id',
        'ia',
        'especialidad_id',
        'estudio_id',
        'tipo_estudio', // Se mantiene como caché legible del nombre del estudio (compatibilidad con vistas existentes)
        'regiones',
        'fecha',
        'hora_estudio',
        'cont_50ml',
        'cont_100ml',
        'jeringa_prellenada',
        'descartables',
        'otros_agujas',
        'resultado',
        'medico_solicitante_id',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_estudio' => 'datetime:H:i',
        'regiones' => 'integer',
        'cont_50ml' => 'integer',
        'cont_100ml' => 'integer',
        'jeringa_prellenada' => 'integer',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico_solicitante()
    {
        return $this->belongsTo(Empleado::class, 'medico_solicitante_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function estudio()
    {
        return $this->belongsTo(Estudio::class, 'estudio_id');
    }

    // Movimientos de stock (insumos) generados automáticamente al cargar este estudio
    public function movimientos_stock()
    {
        return $this->hasMany(Historial_stock::class, 'estudio_medico_id');
    }
}
