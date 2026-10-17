<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Examen;
use App\Models\IntentoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            ->withCount([
                'cursos', // total asignados
                'cursos as cursos_completados_count' => function ($query) {
                    $query->where('curso_user.progreso', 100); // solo los al 100%
                }
            ])
            ->with(['cursos.modulos'])
            ->get()
            ->map(function ($empleado) {
                // Calcular el promedio del progreso global del colaborador
                $totalCursos = $empleado->cursos->count();
                if ($totalCursos > 0) {
                    $sumaProgreso = $empleado->cursos->sum(function ($curso) {
                        return $curso->pivot->progreso ?? 0;
                    });
                    $empleado->progreso = round($sumaProgreso / $totalCursos);
                } else {
                    $empleado->progreso = 0;
                }

                return $empleado;
            });

        // 4. Datos para la pestaña "Gestión de Cursos"
        $cursos = Curso::with(['modulos' => function ($q) {
            $q->orderBy('orden', 'asc');
        }])->withCount(['modulos', 'usuarios'])->get();

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
     * Registra un nuevo alumno/colaborador en el sistema
     */
    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'dni'      => 'required|string|digits:8|unique:users,dni',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'El nombre completo es obligatorio.',
            'dni.required'      => 'El DNI es obligatorio.',
            'dni.digits'        => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'        => 'Este DNI ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $dni = trim($request->input('dni'));

        User::create([
            'name'     => $request->name,
            'dni'      => $dni,
            'email'    => $dni . '@anka.com',
            'password' => Hash::make($request->password),
            'role'     => 'alumno',
        ]);

        return redirect()->back()->with('success', 'Estudiante registrado correctamente.');
    }

    public function actualizarUsuario(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'name'     => 'required|string|max:255',
            'dni'      => 'required|string|digits:8|unique:users,dni,' . $request->user_id,
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'dni.required'  => 'El DNI es obligatorio.',
            'dni.digits'    => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'    => 'Este DNI ya pertenece a otro usuario.',
            'password.min'  => 'La nueva contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->name = $request->name;
        $user->dni  = $request->dni;

        // Actualiza la contraseña únicamente si se envió un valor
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Datos del colaborador actualizados correctamente.');
    }

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

        return redirect()->back()->with('success', 'Curso asignado correctamente.');
    }

    public function actualizarCurso(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $request->validate([
            'curso_id'   => 'required|exists:cursos,id',
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        if ($request->hasFile('imagen')) {
            if ($curso->imagen && file_exists(public_path($curso->imagen))) {
                @unlink(public_path($curso->imagen));
            }

            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $file->move(public_path('img/cursos'), $nombreImagen);
            $curso->imagen = 'img/cursos/' . $nombreImagen;
        }

        $curso->titulo = $request->titulo;
        if ($request->filled('categoria')) $curso->categoria = $request->categoria;
        $curso->descripcion = $request->descripcion;
        $curso->save();

        return redirect()->back()->with('success', 'Curso actualizado correctamente.');
    }

    public function eliminarCurso(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $curso = Curso::with('modulos')->findOrFail($request->curso_id);

        if ($curso->imagen && file_exists(public_path($curso->imagen))) {
            @unlink(public_path($curso->imagen));
        }

        foreach ($curso->modulos as $mod) {
            $pathVideo = $mod->video_url ?? $mod->video;
            if ($pathVideo && file_exists(public_path($pathVideo))) {
                @unlink(public_path($pathVideo));
            }
        }

        $curso->delete();
        return redirect()->back()->with('success', 'Curso eliminado exitosamente.');
    }

    public function guardarModulo(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $request->validate([
            'curso_id'    => 'required|exists:cursos,id',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'video'       => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska|max:1048576',
            'video_url'   => 'nullable|string',
        ]);

        $curso = Curso::findOrFail($request->curso_id);

        $rutaVideo = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $siguienteOrden = $curso->modulos()->count() + 1;
            $nombreVideo = time() . '_m' . $siguienteOrden . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $video->getClientOriginalName());
            $video->move(public_path('videos/cursos'), $nombreVideo);
            $rutaVideo = 'videos/cursos/' . $nombreVideo;
        } elseif ($request->filled('video_url')) {
            $rutaVideo = $request->video_url;
        }

        $siguienteOrden = $curso->modulos()->count() + 1;

        $curso->modulos()->create([
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'video'       => $rutaVideo,
            'video_url'   => $rutaVideo,
            'orden'       => $siguienteOrden,
        ]);

        return redirect()->back()->with('success', 'Módulo guardado correctamente.');
    }

    public function actualizarModulo(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $request->validate([
            'modulo_id'   => 'required|exists:modulos,id',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'video'       => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska|max:1048576',
            'video_url'   => 'nullable|string',
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        $modulo->titulo = $request->titulo;
        $modulo->descripcion = $request->descripcion;

        if ($request->hasFile('video')) {
            $pathAnterior = $modulo->video_url ?? $modulo->video;
            if ($pathAnterior && file_exists(public_path($pathAnterior))) {
                @unlink(public_path($pathAnterior));
            }
            $file = $request->file('video');
            $nombreVideo = time() . '_m' . $modulo->orden . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $file->move(public_path('videos/cursos'), $nombreVideo);
            $modulo->video = 'videos/cursos/' . $nombreVideo;
            $modulo->video_url = 'videos/cursos/' . $nombreVideo;
        } elseif ($request->filled('video_url')) {
            $modulo->video = $request->video_url;
            $modulo->video_url = $request->video_url;
        }

        $modulo->save();
        return redirect()->back()->with('success', 'Módulo actualizado.');
    }

    public function eliminarModulo(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $modulo = Modulo::findOrFail($request->modulo_id);
        $pathVideo = $modulo->video_url ?? $modulo->video;

        if ($pathVideo && file_exists(public_path($pathVideo))) {
            @unlink(public_path($pathVideo));
        }

        $modulo->delete();
        return redirect()->back()->with('success', 'Módulo eliminado.');
    }

    public function subirVideoTemporal(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return response()->json(['message' => 'No tienes permisos.'], 403);
        }

        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska|max:1048576',
        ]);

        $archivo = $request->file('video');
        $token = Str::uuid() . '.' . strtolower($archivo->extension() ?: 'mp4');
        $archivo->move(storage_path('app/tmp_videos'), $token);

        return response()->json(['token' => $token]);
    }

    public function guardarCurso(Request $request)
    {
        if (strtolower(trim(Auth::user()->role ?? '')) !== 'operador') {
            return redirect()->back()->with('warning', 'No tienes permisos.');
        }

        $request->validate([
            'titulo'      => 'required|string|max:255',
            'categoria'   => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'modulos'              => 'required|array|min:1|max:20',
            'modulos.*.titulo'     => 'required|string|max:255',
            'modulos.*.video_token' => ['required', 'regex:/^[a-f0-9\-]{36}\.[a-z0-9]{2,4}$/'],
            'examen.titulo'        => 'required|string|max:255',
            'examen.nota_minima'   => 'required|integer|min:1|max:20',
            'preguntas'            => 'required|array|min:1|max:50',
            'preguntas.*.texto'    => 'required|string',
            'preguntas.*.opciones' => 'required|array|min:2|max:4',
            'preguntas.*.correcta' => 'required|integer|min:0|max:3',
        ]);

        $preguntas = [];
        foreach ($request->input('preguntas') as $i => $p) {
            $opciones = [];
            foreach (($p['opciones'] ?? []) as $indice => $texto) {
                $texto = trim((string) $texto);
                if ($texto !== '') $opciones[(int) $indice] = $texto;
            }
            $preguntas[] = [
                'texto'    => $p['texto'],
                'opciones' => $opciones,
                'correcta' => (int) $p['correcta'],
            ];
        }

        $archivosGuardados = [];

        try {
            DB::transaction(function () use ($request, $preguntas, &$archivosGuardados) {
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

                $orden = 0;
                foreach ($request->input('modulos') as $clave => $datosModulo) {
                    $orden++;
                    $token  = $datosModulo['video_token'];
                    $origen = storage_path('app/tmp_videos/' . $token);

                    if (!is_dir(public_path('videos/cursos'))) {
                        mkdir(public_path('videos/cursos'), 0775, true);
                    }
                    $nombreVideo = time() . '_m' . $orden . '_' . $token;
                    $rutaVideo = 'videos/cursos/' . $nombreVideo;
                    rename($origen, public_path($rutaVideo));
                    $archivosGuardados[] = public_path($rutaVideo);

                    $curso->modulos()->create([
                        'titulo'    => $datosModulo['titulo'],
                        'video'     => $rutaVideo,
                        'video_url' => $rutaVideo,
                        'orden'     => $orden,
                    ]);
                }

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
                if (file_exists($ruta)) @unlink($ruta);
            }
            report($e);
            return redirect()->back()->withInput()->with('warning', 'No se pudo crear el curso.');
        }

        return redirect()->back()->with('success', 'Curso creado exitosamente.');
    }
    public function eliminarUsuario(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Opcional: Desvincular sus cursos asignados antes de borrar
        $user->cursos()->detach();

        $user->delete();

        return redirect()->back()->with('success', 'Colaborador eliminado correctamente.');
    }
}
