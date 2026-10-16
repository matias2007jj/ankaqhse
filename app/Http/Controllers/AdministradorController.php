<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\IntentoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministradorController extends Controller
{
    public function panel()
    {
        // Roles administrativos/sistema excluidos del panel del alumno/monitoreo
        $rolesExcluidos = ['admin', 'administrador', 'operador'];

        // 1. Métricas de las Tarjetas Superiores (Se excluye completamente al operador)
        $totalColaboradores = User::whereNotIn(DB::raw('LOWER(TRIM(role))'), $rolesExcluidos)->count();
        $capacitacionesActivas = Curso::count();
        
        // Tasa de cumplimiento global (Promedio de exámenes/cursos aprobados)
        $totalIntentos = IntentoExamen::count();
        $intentosAprobados = IntentoExamen::where('aprobado', true)->count();
        $tasaCumplimiento = $totalIntentos > 0 ? round(($intentosAprobados / $totalIntentos) * 100) : 0;

        $certificadosAprobados = $intentosAprobados;

        // 2. Datos para la pestaña "Métrica General" (Avance por Área o Departamento, excluyendo operador/admin)
        $avancePorArea = User::select('area', DB::raw('count(*) as total_empleados'))
            ->whereNotIn(DB::raw('LOWER(TRIM(role))'), $rolesExcluidos)
            ->whereNotNull('area')
            ->groupBy('area')
            ->get();

        // 3. Datos para la pestaña "Monitoreo Empleados" (Solo colaboradores/alumnos reales)
        $empleados = User::whereNotIn(DB::raw('LOWER(TRIM(role))'), $rolesExcluidos)
            ->withCount('cursos')
            ->with('cursos')
            ->get();

        // 4. Datos para la pestaña "Gestión de Cursos"
        $cursos = Curso::withCount(['modulos', 'usuarios'])->get();

        return view('administrador.panel', compact(
            'totalColaboradores',
            'capacitacionesActivas',
            'tasaCumplimiento',
            'certificadosAprobados',
            'avancePorArea',
            'empleados',
            'cursos'
        ));
    }

    /**
     * Procesa la asignación de un curso a un colaborador con validación antierrores
     */
    public function asignarCurso(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Validar si el usuario ya tiene este curso asignado
        if ($user->cursos()->where('curso_id', $request->curso_id)->exists()) {
            return redirect()->back()->with('warning', 'El colaborador ya tiene asignado este curso.');
        }

        // Vincula el curso al usuario en la tabla pivote curso_user
        $user->cursos()->syncWithoutDetaching([
            $request->curso_id => ['progreso' => 0]
        ]);

        return redirect()->back()->with('success', 'Curso asignado correctamente al colaborador.');
    }
}