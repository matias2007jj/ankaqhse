<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\IntentoExamen;
use App\Models\Modulo;
use App\Models\User;
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

    /**
     * Pantalla del curso: lista de módulos, reproductor de video y evaluación.
     * Los módulos se desbloquean en orden y la evaluación al terminar todos.
     */
    public function curso(Request $request, Curso $curso)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $asignado = $this->estaAsignado($user, $curso);
        $esStaff  = in_array(strtolower(trim($user->role ?? '')), ['operador', 'admin', 'administrador']);

        abort_unless($asignado || $esStaff, 403, 'Este curso no está asignado a tu cuenta.');

        $modulos = $curso->modulos()->orderBy('orden')->orderBy('id')->get();
        $examen  = $curso->examenes()->orderBy('id')->first();

        $completados = $user->modulosCompletados()
            ->where('modulos.curso_id', $curso->id)
            ->pluck('modulos.id')
            ->all();

        // Índice del primer módulo sin completar (= cantidad de módulos si ya están todos)
        $indicePendiente = $modulos->count();
        foreach ($modulos as $i => $m) {
            if (!in_array($m->id, $completados)) {
                $indicePendiente = $i;
                break;
            }
        }
        $todosCompletos = $indicePendiente >= $modulos->count();

        // Hasta qué módulo puede entrar (el personal sin asignación ve todo en modo vista previa)
        $limiteModulos = $asignado
            ? min($indicePendiente, $modulos->count() - 1)
            : $modulos->count() - 1;
        $examenDesbloqueado = $asignado ? $todosCompletos : true;

        // Qué mostrar: módulo, examen o vacío
        $moduloActual = null;
        if ($request->filled('modulo')) {
            $idx = $modulos->search(fn($m) => (int) $m->id === (int) $request->input('modulo'));
            if ($idx !== false && $idx <= $limiteModulos) {
                $moduloActual = $modulos[$idx];
            }
        }

        if ($request->boolean('examen') && $examen && $examenDesbloqueado) {
            $vista = 'examen';
        } elseif ($moduloActual) {
            $vista = 'modulo';
        } elseif ($modulos->isEmpty()) {
            $vista = $examen ? 'examen' : 'vacio';
        } elseif ($asignado && $todosCompletos && $examen) {
            $vista = 'examen';
        } else {
            $moduloActual = $modulos[$limiteModulos];
            $vista = 'modulo';
        }

        $indiceActual = $moduloActual ? $modulos->search(fn($m) => (int) $m->id === (int) $moduloActual->id) : null;
        $siguiente    = ($indiceActual !== null) ? ($modulos[$indiceActual + 1] ?? null) : null;

        // Evaluación: intentos del alumno
        $intentos = $examen
            ? IntentoExamen::where('user_id', $user->id)->where('examen_id', $examen->id)->orderByDesc('id')->get()
            : collect();
        $aprobado = $intentos->contains('aprobado', true);
        $mejor    = $intentos->sortByDesc('puntaje')->first();

        if ($vista === 'examen' && $examen) {
            $examen->load('preguntas.respuestas');
            // Mezclar opciones; la vista nunca imprime es_correcta
            $examen->preguntas->each(function ($p) {
                $p->setRelation('respuestas', $p->respuestas->shuffle()->values());
            });
        }

        $progreso = 0;
        if ($asignado) {
            $pivot = $user->cursos()->where('cursos.id', $curso->id)->first();
            $progreso = (int) ($pivot->pivot->progreso ?? 0);
        }

        return view('capacitaciones.curso', compact(
            'curso', 'modulos', 'examen', 'completados', 'asignado',
            'limiteModulos', 'examenDesbloqueado', 'vista', 'moduloActual',
            'indiceActual', 'siguiente', 'aprobado', 'mejor', 'progreso'
        ));
    }

    /**
     * Marca un módulo como completado y avanza al siguiente.
     */
    public function completarModulo(Curso $curso, Modulo $modulo)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($this->estaAsignado($user, $curso), 403, 'Este curso no está asignado a tu cuenta.');
        abort_unless((int) $modulo->curso_id === (int) $curso->id, 404);

        $ids = $curso->modulos()->orderBy('orden')->orderBy('id')->pluck('id')->all();
        $posicion = array_search($modulo->id, $ids);

        // Todos los módulos anteriores deben estar completos
        $anteriores = array_slice($ids, 0, $posicion);
        $hechos = $user->modulosCompletados()->whereIn('modulos.id', $anteriores)->count();
        if ($hechos < count($anteriores)) {
            return redirect()->route('alumno.curso', $curso->id)
                ->with('warning', 'Primero debes completar los módulos anteriores.');
        }

        $user->modulosCompletados()->syncWithoutDetaching([
            $modulo->id => ['completado_el' => now()],
        ]);

        $this->recalcularProgreso($user, $curso);

        $siguienteId = $ids[$posicion + 1] ?? null;
        if ($siguienteId) {
            return redirect()->route('alumno.curso', ['curso' => $curso->id, 'modulo' => $siguienteId])
                ->with('success', 'Módulo completado.');
        }

        $tieneExamen = $curso->examenes()->exists();

        return redirect()->route('alumno.curso', $tieneExamen ? ['curso' => $curso->id, 'examen' => 1] : ['curso' => $curso->id])
            ->with('success', $tieneExamen
                ? 'Terminaste todos los módulos. Ahora rinde la evaluación.'
                : 'Terminaste todos los módulos del curso.');
    }

    /**
     * Califica la evaluación en el servidor (escala 0-20).
     */
    public function rendirExamen(Request $request, Curso $curso)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($this->estaAsignado($user, $curso), 403, 'Este curso no está asignado a tu cuenta.');

        $examen = $curso->examenes()->with('preguntas.respuestas')->orderBy('id')->firstOrFail();

        $totalModulos = $curso->modulos()->count();
        $hechos = $user->modulosCompletados()->where('modulos.curso_id', $curso->id)->count();
        if ($hechos < $totalModulos) {
            return redirect()->route('alumno.curso', $curso->id)
                ->with('warning', 'Debes completar todos los módulos antes de rendir la evaluación.');
        }

        $request->validate(['respuestas' => 'required|array'], [
            'respuestas.required' => 'Responde todas las preguntas antes de enviar.',
        ]);

        $total = $examen->preguntas->count();
        $correctas = 0;

        foreach ($examen->preguntas as $pregunta) {
            $elegida = (int) $request->input("respuestas.{$pregunta->id}");
            $opcion = $pregunta->respuestas->firstWhere('id', $elegida);
            if ($opcion && $opcion->es_correcta) {
                $correctas++;
            }
        }

        $puntaje = $total > 0 ? (int) round(($correctas / $total) * 20) : 0;
        $aprobado = $puntaje >= $examen->nota_minima;

        IntentoExamen::create([
            'user_id'       => $user->id,
            'examen_id'     => $examen->id,
            'puntaje'       => $puntaje,
            'aprobado'      => $aprobado,
            'completado_el' => now(),
        ]);

        $this->recalcularProgreso($user, $curso);

        return redirect()->route('alumno.curso', ['curso' => $curso->id, 'examen' => 1])
            ->with('resultado', [
                'puntaje'     => $puntaje,
                'correctas'   => $correctas,
                'total'       => $total,
                'aprobado'    => $aprobado,
                'nota_minima' => $examen->nota_minima,
            ]);
    }

    private function estaAsignado(User $user, Curso $curso): bool
    {
        return $user->cursos()->where('cursos.id', $curso->id)->exists();
    }

    /**
     * Progreso = pasos hechos / pasos totales, donde cada módulo es un paso
     * y la evaluación aprobada es el último. 100% solo con la evaluación aprobada.
     */
    private function recalcularProgreso(User $user, Curso $curso): void
    {
        $totalModulos = $curso->modulos()->count();
        $hechos = $user->modulosCompletados()->where('modulos.curso_id', $curso->id)->count();

        $examen = $curso->examenes()->orderBy('id')->first();
        $examenAprobado = $examen
            && IntentoExamen::where('user_id', $user->id)->where('examen_id', $examen->id)->where('aprobado', true)->exists();

        $pasos = $totalModulos + ($examen ? 1 : 0);
        $hecho = $hechos + ($examenAprobado ? 1 : 0);
        $progreso = $pasos > 0 ? (int) round(($hecho / $pasos) * 100) : 0;

        $user->cursos()->updateExistingPivot($curso->id, ['progreso' => $progreso]);
    }
}
