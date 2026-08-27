<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'es_modalidad_imagen',
    ];

    protected $casts = [
        'es_modalidad_imagen' => 'boolean',
    ];

    public function procedimientos()
    {
        return $this->hasMany(Procedimiento::class, 'especialidad_id');
    }

    public function procedimientos_secundarios()
    {
        return $this->hasMany(Procedimiento::class, 'especialidad_2_id');
    }

    // Estudios de Diagnóstico por Imagen que pertenecen a esta modalidad (Rayos / Tomografía)
    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'especialidad_id');
    }

    // Solo las especialidades marcadas como modalidad de Diagnóstico por Imagen (Rayos, Tomografía)
    public function scopeModalidadesImagen($query)
    {
        return $query->where('es_modalidad_imagen', true);
    }

    // Solo las especialidades quirúrgicas (todas menos Rayos/Tomografía). Para usar en
    // cirugías: creación/edición y el filtro de Estadísticas de Cirugías, donde
    // Rayos/Tomografía no tienen nada que hacer (son de Diagnóstico por Imágenes).
    public function scopeQuirurgicas($query)
    {
        return $query->where('es_modalidad_imagen', false);
    }
}