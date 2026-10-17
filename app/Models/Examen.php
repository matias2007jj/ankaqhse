<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examenes';

    protected $fillable = [
        'curso_id',
        'titulo',
        'nota_minima',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'examen_id');
    }
}
