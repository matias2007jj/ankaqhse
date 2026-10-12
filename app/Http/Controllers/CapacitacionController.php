<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CapacitacionController extends Controller
{
    public function inicio()
    {
        $cursos = [
            [
                'id' => 1,
                'title' => 'Ciberseguridad y Protección de Datos',
                'category' => 'Compliance & Seguridad',
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=600&q=80',
                'enrolled' => true,
                'examPassed' => false,
                'examDate' => null,
                'lessons' => [
                    ['id' => 101, 'title' => '1. Principios de Phishing y Malware', 'completed' => true],
                    ['id' => 102, 'title' => '2. Gestión de Contraseñas Seguras', 'completed' => true],
                    ['id' => 103, 'title' => '3. Normativa de Datos Personales (GDPR)', 'completed' => true],
                    ['id' => 104, 'title' => '4. Protocolo ante Incidentes de Seguridad', 'completed' => false],
                ],
                'examQuestions' => [
                    ['q' => '¿Qué es un ataque de Phishing?', 'options' => ['Un virus en el hardware', 'Un intento de engaño mediante correos falsos', 'Un programa de backup'], 'correct' => 1],
                    ['q' => '¿Cuál es la longitud mínima recomendada para una clave segura?', 'options' => ['6 caracteres', '8 caracteres simples', '12 caracteres con símbolos y números'], 'correct' => 2],
                ]
            ],
            [
                'id' => 2,
                'title' => 'Liderazgo y Trabajo en Equipo',
                'category' => 'Habilidades Blandas',
                'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80',
                'enrolled' => true,
                'examPassed' => true,
                'examDate' => '02/10/2026',
                'lessons' => [
                    ['id' => 201, 'title' => '1. Comunicación Asertiva', 'completed' => true],
                    ['id' => 202, 'title' => '2. Gestión de Conflictos en la Oficina', 'completed' => true],
                    ['id' => 203, 'title' => '3. Delegación Efectiva de Tareas', 'completed' => true],
                ],
                'examQuestions' => [
                    ['q' => '¿Qué caracteriza a la comunicación asertiva?', 'options' => ['Expresar ideas con claridad y respeto', 'Imponer siempre la opinión personal', 'Evitar hablar de problemas'], 'correct' => 0]
                ]
            ],
            [
                'id' => 3,
                'title' => 'Prevención de Riesgos Laborales',
                'category' => 'Seguridad Laboral',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=600&q=80',
                'enrolled' => false,
                'examPassed' => false,
                'examDate' => null,
                'lessons' => [
                    ['id' => 301, 'title' => '1. Ergonomía en el puesto de trabajo', 'completed' => false],
                    ['id' => 302, 'title' => '2. Plan de Evacuación ante Sismos e Incendios', 'completed' => false],
                    ['id' => 303, 'title' => '3. Uso de Equipos de Protección', 'completed' => false],
                ],
                'examQuestions' => [
                    ['q' => '¿Qué se debe hacer en caso de evacuación?', 'options' => ['Usar el ascensor inmediatamente', 'Mantener la calma y usar escaleras', 'Regresar por objetos personales'], 'correct' => 1]
                ]
            ]
        ];

        return view('capacitaciones.inicio', compact('cursos'));
    }
}