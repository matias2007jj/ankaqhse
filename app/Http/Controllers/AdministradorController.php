<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\IntentoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
    /**
     * Recibe UN video en segundo plano apenas el operador lo elige (con barra de progreso).
     * Lo deja en una carpeta temporal y devuelve un token; al guardar el curso solo se mueve.
     */
    public function subirVideoTemporal(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return response()->json(['message' => 'No tienes permisos.'], 403);
        }

        $request->validate([
           'video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime|max:1048576',
        ], [
            'video.max' => 'El video puede pesar como máximo 1 GB.',
            'video.uploaded' => 'No se pudo subir el video. Revisa upload_max_filesize y post_max_size de PHP.',
        ]);

        $archivo = $request->file('video');
        $token = Str::uuid() . '.' . strtolower($archivo->extension() ?: 'mp4');
        $archivo->move(storage_path('app/tmp_videos'), $token);

        return response()->json(['token' => $token]);
    }

    /**
     * Crea un curso completo: datos, módulos (con un video cada uno) y evaluación.
     * EXCLUSIVO PARA OPERADOR
     */
    public function guardarCurso(Request $request)
    {
        // Bloquear si no es operador
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos para crear cursos.');
        }

        $request->validate([
            // Datos generales
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072', // Máx 3MB

            // Módulos: cada uno con su video (máx. 200 MB por video)
            'modulos'              => 'required|array|min:1|max:20',
            'modulos.*.titulo'     => 'required|string|max:255',
            'modulos.*.video_token' => ['required', 'regex:/^[a-f0-9\-]{36}\.[a-z0-9]{2,4}$/'],

            // Evaluación
            'examen.titulo'        => 'required|string|max:255',
            'examen.nota_minima'   => 'required|integer|min:1|max:20',
            'preguntas'            => 'required|array|min:1|max:50',
            'preguntas.*.texto'    => 'required|string',
            'preguntas.*.opciones' => 'required|array|min:2|max:4',
            'preguntas.*.opciones.*' => 'nullable|string|max:255',
            'preguntas.*.correcta' => 'required|integer|min:0|max:3',
        ], [
            'modulos.required'            => 'Debes agregar al menos un módulo.',
            'modulos.*.titulo.required'   => 'Cada módulo necesita un título.',
            'modulos.*.video_token.required' => 'Cada módulo necesita un video (espera a que termine de subir).',
            'modulos.*.video_token.regex'    => 'Video inválido, vuelve a subirlo.',
            'preguntas.required'          => 'La evaluación necesita al menos una pregunta.',
            'preguntas.*.texto.required'  => 'Todas las preguntas necesitan un enunciado.',
        ]);

        // Validación de preguntas: mínimo 2 opciones con texto y la correcta debe tener texto
        $preguntas = [];
        foreach ($request->input('preguntas') as $i => $p) {
            $opciones = [];
            foreach (($p['opciones'] ?? []) as $indice => $texto) {
                $texto = trim((string) $texto);
                if ($texto !== '') {
                    $opciones[(int) $indice] = $texto;
                }
            }

            $numero = $i + 1;
            if (count($opciones) < 2) {
                return redirect()->back()->withInput()
                    ->withErrors(["preguntas.$i" => "La pregunta $numero necesita al menos 2 opciones."]);
            }
            if (!isset($opciones[(int) $p['correcta']])) {
                return redirect()->back()->withInput()
                    ->withErrors(["preguntas.$i" => "La pregunta $numero tiene marcada como correcta una opción vacía."]);
            }

            $preguntas[] = [
                'texto'    => $p['texto'],
                'opciones' => $opciones,
                'correcta' => (int) $p['correcta'],
            ];
        }

        $archivosGuardados = []; // para limpiar si algo falla

        try {
            DB::transaction(function () use ($request, $preguntas, &$archivosGuardados) {

                // Portada
                $rutaImagen = null;
                if ($request->hasFile('imagen')) {
                    $file = $request->file('imagen');
                    $nombreImagen = time() . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
                    $file->move(public_path('img/cursos'), $nombreImagen);
                    $rutaImagen = 'img/cursos/' . $nombreImagen;
                    $archivosGuardados[] = public_path($rutaImagen);
                }

                $curso = Curso::create([
                    'titulo'      => $request->titulo,
                    'categoria'   => $request->categoria,
                    'descripcion' => $request->descripcion,
                    'imagen'      => $rutaImagen,
                ]);

                // Módulos con su video
                $orden = 0;
                foreach ($request->input('modulos') as $clave => $datosModulo) {
                    $orden++;
                    // El video ya fue subido en segundo plano: solo se mueve (instantáneo)
                    $token  = $datosModulo['video_token'];
                    $origen = storage_path('app/tmp_videos/' . $token);
                    if (!file_exists($origen)) {
                        throw new \RuntimeException("El video del módulo $orden no está disponible.");
                    }

                    if (!is_dir(public_path('videos/cursos'))) {
                        mkdir(public_path('videos/cursos'), 0775, true);
                    }
                    $nombreVideo = time() . '_m' . $orden . '_' . $token;
                    $rutaVideo = 'videos/cursos/' . $nombreVideo;
                    rename($origen, public_path($rutaVideo));
                    $archivosGuardados[] = public_path($rutaVideo);

                    $curso->modulos()->create([
                        'titulo' => $datosModulo['titulo'],
                        'video'  => $rutaVideo,
                        'orden'  => $orden,
                    ]);
                }

                // Evaluación
                $examen = Examen::create([
                    'curso_id'    => $curso->id,
                    'titulo'      => $request->input('examen.titulo'),
                    'nota_minima' => $request->input('examen.nota_minima'),
                ]);

                foreach ($preguntas as $p) {
                    $pregunta = $examen->preguntas()->create(['pregunta' => $p['texto']]);

                    foreach ($p['opciones'] as $indiceOpcion => $textoOpcion) {
                        $pregunta->respuestas()->create([
                            'respuesta'   => $textoOpcion,
                            'es_correcta' => $indiceOpcion === $p['correcta'],
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            foreach ($archivosGuardados as $ruta) {
                if (file_exists($ruta)) {
                    @unlink($ruta);
                }
            }
            report($e);

            return redirect()->back()->withInput()
                ->with('warning', 'No se pudo crear el curso. No se guardó nada; inténtalo nuevamente.');
        }

        return redirect()->back()->with('success', 'Curso creado con sus módulos, videos y evaluación.');
    }
}