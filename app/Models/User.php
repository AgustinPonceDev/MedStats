<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'servicio_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * Get the user's profile/role.
     */
    public function perfil()
    {
        return $this->belongsTo(UsuarioPerfil::class, 'role');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    /**
     * Check if the user has access to a specific module.
     *
     * @param string|array $modules
     * @return bool
     */
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
     * Devuelve el servicio al que este usuario está restringido, o null si tiene
     * acceso global. Prioridad: si el usuario tiene su propio servicio_id asignado
     * (caso puntual, ej. un admin que además cubre un servicio), ese gana. Si no,
     * hereda el servicio asignado al PERFIL/rol (ej. "Diagnóstico de Imagen" con
     * servicio "Diagnóstico por imágenes" — así no hay que asignarlo usuario por
     * usuario, alcanza con asignarlo una vez en el perfil).
     */
    public function servicioRestringido(): ?int
    {
        return $this->servicio_id ?: optional($this->perfil)->servicio_id;
    }
}
