<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'servicio_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function perfil()
    {
        return $this->belongsTo(UsuarioPerfil::class, 'role');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function hasAccess($modules)
    {
        if (empty($modules)) {
            return true;
        }

        if (is_string($modules)) {
            $modules = [$modules];
        }

        $perfil = $this->perfil;

        if (!$perfil) {
            return false;
        }

        foreach ($modules as $module) {
            if ($perfil->$module) {
                return true;
            }
        }

        return false;
    }

    /**
     * Servicio al que este usuario está restringido, o null si tiene acceso
     * global. Se define ÚNICAMENTE en el usuario (users.servicio_id, asignado
     * desde /usuarios/edit) — no hay herencia por perfil/rol.
     */
    public function servicioRestringido(): ?int
    {
        return $this->servicio_id;
    }
}
