<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = ['titulo', 'objetivo', 'descripcion', 'imagen', 'estado', 'activo'];

    /**
     * Relación: Un curso tiene muchos módulos
     */
    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'curso_id');
    }

    /**
     * Relación: Un curso tiene muchos exámenes
     */
    public function examenes()
    {
        return $this->hasMany(Examen::class, 'curso_id');
    }

    /**
     * Relación: Un curso puede pertenecer a varios usuarios (alumnos)
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'curso_user', 'curso_id', 'user_id')
                    ->withPivot('progreso')
                    ->withTimestamps();
    }
}