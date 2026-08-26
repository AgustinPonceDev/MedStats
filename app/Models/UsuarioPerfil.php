<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPerfil extends Model
{
    use HasFactory;

    protected $fillable =  [
        'perfil',
        'admin',
        'insumos',
        'estadisticas',
        'pacientes',
        'camas',
        'cirugias',
        'estudios_medicos',
        'servicio_id'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
