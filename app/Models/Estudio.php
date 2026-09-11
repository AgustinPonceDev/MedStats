<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    use HasFactory;

    protected $fillable = [
        'especialidad_id',
        'nombre',
    ];

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    public function estudios_medicos()
    {
        return $this->hasMany(EstudioMedico::class, 'estudio_id');
    }
}
