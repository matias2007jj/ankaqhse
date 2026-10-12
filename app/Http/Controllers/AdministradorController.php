<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function panel()
    {
        // Métricas clave para el panel
        $metricas = [
            'total_empleados' => 48,
            'cursos_activos' => 6,
            'tasa_cumplimiento' => 82, // Porcentaje %
            'certificados_emitidos' => 34,
        ];

        // Resumen por departamento
        $departamentos = [
            ['nombre' => 'Operaciones y Logística', 'empleados' => 18, 'completado' => 88],
            ['nombre' => 'Seguridad y Salud en el Trabajo (SST)', 'empleados' => 12, 'completado' => 95],
            ['nombre' => 'Tecnología y Sistemas', 'empleados' => 10, 'completado' => 70],
            ['nombre' => 'Administración y Finanzas', 'empleados' => 8, 'completado' => 75],
        ];

        // Listado de empleados con su avance
        $empleados = [
            [
                'id' => 1,
                'nombre' => 'Ana Martínez',
                'email' => 'admin@empresa.com',
                'puesto' => 'Analista de Procesos',
                'departamento' => 'Operaciones y Logística',
                'cursos_asignados' => 3,
                'cursos_completados' => 2,
                'progreso_general' => 75,
                'estado' => 'Al día'
            ],
            [
                'id' => 2,
                'nombre' => 'Carlos Mendoza',
                'email' => 'carlos@empresa.com',
                'puesto' => 'Supervisora de SST',
                'departamento' => 'Seguridad y Salud en el Trabajo (SST)',
                'cursos_asignados' => 2,
                'cursos_completados' => 2,
                'progreso_general' => 100,
                'estado' => 'Completado'
            ],
            [
                'id' => 3,
                'nombre' => 'Roberto Gómez',
                'email' => 'roberto@empresa.com',
                'puesto' => 'Asistente de Logística',
                'departamento' => 'Operaciones y Logística',
                'cursos_asignados' => 3,
                'cursos_completados' => 1,
                'progreso_general' => 33,
                'estado' => 'Pendiente'
            ],
            [
                'id' => 4,
                'nombre' => 'Lucía Fernández',
                'email' => 'lucia@empresa.com',
                'puesto' => 'Desarrolladora Frontend',
                'departamento' => 'Tecnología y Sistemas',
                'cursos_asignados' => 2,
                'cursos_completados' => 1,
                'progreso_general' => 50,
                'estado' => 'En curso'
            ]
        ];

        // Cursos registrados
        $cursos = [
            [
                'id' => 1,
                'titulo' => 'Ciberseguridad y Protección de Datos',
                'categoria' => 'Compliance & Seguridad',
                'modulos' => 4,
                'inscritos' => 32,
                'estado' => 'Publicado'
            ],
            [
                'id' => 2,
                'titulo' => 'Liderazgo y Trabajo en Equipo',
                'categoria' => 'Habilidades Blandas',
                'modulos' => 3,
                'inscritos' => 40,
                'estado' => 'Publicado'
            ],
            [
                'id' => 3,
                'titulo' => 'Prevención de Riesgos Laborales',
                'categoria' => 'Seguridad Laboral',
                'modulos' => 3,
                'inscritos' => 48,
                'estado' => 'Publicado'
            ]
        ];

        return view('administrador.panel', compact('metricas', 'departamentos', 'empleados', 'cursos'));
    }

    public function guardarCurso(Request $request)
    {
        return back()->with('success', 'Curso creado y publicado correctamente en Anka Academy.');
    }

    public function asignarCurso(Request $request)
    {
        return back()->with('success', 'Capacitación asignada exitosamente.');
    }
}