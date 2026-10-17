<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuestas';

    protected $fillable = [
        'pregunta_id',
        'respuesta',
        'es_correcta',
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }
}
