<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cargo',
        'area',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación de Cursos asignados
     */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'curso_user', 'user_id', 'curso_id')
                    ->withPivot('progreso')
                    ->withTimestamps();
    }

    /**
     * Módulos que el usuario ya completó (vio su video)
     */
    public function modulosCompletados()
    {
        return $this->belongsToMany(Modulo::class, 'modulo_usuario', 'user_id', 'modulo_id')
                    ->withPivot('completado_el')
                    ->withTimestamps();
    }
}
