<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\IntentoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdministradorController extends Controller
{
    public function panel()
    {
        // Roles administrativos/sistema excluidos del panel del alumno/monitoreo
        $rolesExcluidos = ['admin', 'administrador', 'operador'];

        // 1. Métricas de las Tarjetas Superiores
        $totalColaboradores = User::whereNotIn(DB::raw('LOWER(TRIM(role))'), $rolesExcluidos)->count();
        $capacitacionesActivas = Curso::count();
        
        // Tasa de cumplimiento global
        $totalIntentos = IntentoExamen::count();
        $intentosAprobados = IntentoExamen::where('aprobado', true)->count();
        $tasaCumplimiento = $totalIntentos > 0 ? round(($intentosAprobados / $totalIntentos) * 100) : 0;

        $certificadosAprobados = $intentosAprobados;

        // 2. Datos para la pestaña "Métrica General"
        $avancePorArea = User::select('area', DB::raw('count(*) as total_empleados'))
            ->whereNotIn(DB::raw('LOWER(TRIM(role))'), $rolesExcluidos)
            ->whereNotNull('area')
            ->groupBy('area')
            ->get();

        // 3. Datos para la pestaña "Monitoreo Empleados"
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
     * Procesa la asignación de un curso a un colaborador
     */
    public function asignarCurso(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->cursos()->where('curso_id', $request->curso_id)->exists()) {
            return redirect()->back()->with('warning', 'El colaborador ya tiene asignado este curso.');
        }

        $user->cursos()->syncWithoutDetaching([
            $request->curso_id => ['progreso' => 0]
        ]);

        return redirect()->back()->with('success', 'Curso asignado correctamente al colaborador.');
    }

    /**
     * Procesa la creación de un nuevo curso con imagen (EXCLUSIVO PARA OPERADOR)
     */
    public function actualizarCurso(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos para editar cursos.');
        }

        $request->validate([
            'curso_id'   => 'required|exists:cursos,id',
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe físicamente
            if ($curso->imagen && file_exists(public_path($curso->imagen))) {
                @unlink(public_path($curso->imagen));
            }

            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $file->move(public_path('img/cursos'), $nombreImagen);
            $curso->imagen = 'img/cursos/' . $nombreImagen;
        }

        $curso->titulo = $request->titulo;
        $curso->categoria = $request->categoria;
        $curso->descripcion = $request->descripcion;
        $curso->save();

        return redirect()->back()->with('success', 'Curso actualizado correctamente.');
    }
    public function guardarCurso(Request $request)
    {
        // Bloquear si no es operador
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos para crear cursos.');
        }

        $request->validate([
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072', // Máx 3MB
        ]);

        $rutaImagen = null;

        // Subida de imagen a la carpeta public/img/cursos
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $file->move(public_path('img/cursos'), $nombreImagen);
            $rutaImagen = 'img/cursos/' . $nombreImagen;
        }

        Curso::create([
            'titulo'      => $request->titulo,
            'categoria'   => $request->categoria,
            'descripcion' => $request->descripcion,
            'imagen'      => $rutaImagen,
        ]);

        return redirect()->back()->with('success', 'Curso creado exitosamente con su portada.');
    }
}