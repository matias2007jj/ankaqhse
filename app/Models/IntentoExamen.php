<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntentoExamen extends Model
{
    protected $table = 'intentos_examen';

    protected $fillable = [
        'user_id',
        'examen_id',
        'puntaje',
        'aprobado',
        'completado_el',
    ];

    protected $casts = [
        'aprobado'      => 'boolean',
        'completado_el' => 'datetime',
    ];

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }
}
