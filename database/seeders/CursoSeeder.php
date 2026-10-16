<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $curso1 = Curso::create([
            'titulo' => 'Seguridad y Salud en el Trabajo (SST)',
            'categoria' => 'Normativa Obligatoria',
            'descripcion' => 'Capacitación sobre normativas básicas de prevención de riesgos laborales.',
            'estado' => 'activo'
        ]);

        $curso1->modulos()->createMany([
            ['titulo' => 'Módulo 1: Introducción a la SST', 'orden' => 1],
            ['titulo' => 'Módulo 2: Prevención de Incendios y Evacuación', 'orden' => 2],
        ]);

        $curso2 = Curso::create([
            'titulo' => 'Atención al Cliente y Calidad de Servicio',
            'categoria' => 'Habilidades Blandas',
            'descripcion' => 'Técnicas efectivas para la resolución de conflictos y servicio de excelencia.',
            'estado' => 'activo'
        ]);

        $curso2->modulos()->createMany([
            ['titulo' => 'Módulo 1: Comunicación Asertiva', 'orden' => 1],
        ]);
    }
}