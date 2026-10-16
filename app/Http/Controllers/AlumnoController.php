<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    public function inicio()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cargar los cursos asignados al colaborador logueado a través de la tabla pivote curso_user
        $cursos = $user ? $user->cursos()->withCount('modulos')->get() : collect();

        // Calcular promedio de progreso global del alumno
        $progresoGlobal = $cursos->count() > 0 ? round($cursos->avg('pivot.progreso')) : 0;
        $cursosCompletados = $cursos->filter(fn($c) => ($c->pivot->progreso ?? 0) == 100)->count();

        return view('capacitaciones.inicio', compact('user', 'cursos', 'progresoGlobal', 'cursosCompletados'));
    }
}