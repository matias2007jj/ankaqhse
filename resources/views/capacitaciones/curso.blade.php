@extends('plantillas.aplicacion')

@section('titulo', $curso->titulo . ' - ANKA ACADEMY')

@push('scripts')
<script src="{{ asset('js/curso-alumno.js') }}"></script>
@endpush

@section('contenido')
@php
    $resultado = session('resultado');
    $totalModulos = $modulos->count();
    $hechosCount = count($completados);
@endphp

<div class="space-y-6">

    <a href="{{ route('inicio') }}" class="inline-flex items-center gap-1 text-xs font-bold text-neutral-500 hover:text-orange-600 transition-all">
        ← Volver a mis capacitaciones
    </a>

    <!-- CABECERA DEL CURSO -->
    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full self-start sm:self-auto">{{ $curso->categoria ?? $curso->objetivo ?? 'Capacitación' }}</span>
            <div class="flex items-center gap-2">
                @if($asignado)
                    @if($progreso == 100 || $aprobado)
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">✓ Aprobado</span>
                    @elseif($progreso > 0)
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">En curso</span>
                    @else
                        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">Pendiente</span>
                    @endif
                @else
                    <span class="bg-neutral-100 text-neutral-600 text-xs font-bold px-2.5 py-1 rounded-full">Vista previa</span>
                @endif
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-2xl font-black text-neutral-900">{{ $curso->titulo }}</h1>
                @if($curso->descripcion)
                    <p class="text-sm text-neutral-500">{{ $curso->descripcion }}</p>
                @endif
            </div>

            <!-- BOTÓN DESCARGAR CERTIFICADO EN CABECERA CUANDO ESTÁ APROBADO -->
            @if($asignado && ($aprobado || $progreso == 100))
                <div class="shrink-0">
                    <a href="{{ route('certificado.descargar', $curso->id) }}" 
                       class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs inline-flex items-center gap-2 shadow-md transition-all">
                        <i data-lucide="award" class="w-4 h-4"></i> Descargar Certificado PDF
                    </a>
                </div>
            @endif
        </div>

        @if($asignado)
        <div class="space-y-1.5 pt-1">
            <div class="flex justify-between items-center text-xs">
                <span class="font-semibold text-neutral-500">Avance ({{ $hechosCount }} de {{ $totalModulos }} módulos{{ $examen ? ' + evaluación' : '' }})</span>
                <span class="font-bold text-neutral-800">{{ $progreso }}%</span>
            </div>
            <div class="w-full bg-neutral-100 rounded-full h-2 overflow-hidden">
                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progreso }}%;"></div>
            </div>
        </div>
        @endif
    </div>

    <!-- ALERTAS -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl text-sm font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-sm font-semibold">{{ session('warning') }}</div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm font-semibold">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LISTA DE MÓDULOS -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-4 space-y-2">
                <h2 class="text-xs font-bold text-neutral-400 uppercase tracking-wider px-2 pb-1">Contenido del curso</h2>

                @foreach($modulos as $i => $m)
                    @php
                        $hecho = in_array($m->id, $completados);
                        $abierto = $i <= $limiteModulos;
                        $esActual = $vista === 'modulo' && $moduloActual && (int) $moduloActual->id === (int) $m->id;
                    @endphp
                    <a href="{{ $abierto ? route('alumno.curso', ['curso' => $curso->id, 'modulo' => $m->id]) : 'javascript:void(0)' }}"
                       @if(!$abierto) aria-disabled="true" @endif
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all
                              {{ $esActual ? 'bg-orange-50 border border-orange-200' : 'border border-transparent' }}
                              {{ $abierto ? 'hover:bg-neutral-50 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                        <span class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-xs font-bold
                                     {{ $hecho ? 'bg-green-100 text-green-700' : ($abierto ? 'bg-orange-100 text-orange-700' : 'bg-neutral-100 text-neutral-400') }}">
                            {{ $hecho ? '✓' : ($abierto ? $i + 1 : '🔒') }}
                        </span>
                        <span class="font-semibold {{ $esActual ? 'text-orange-700' : 'text-neutral-700' }}">{{ $m->titulo }}</span>
                    </a>
                @endforeach

                @if($examen)
                    <a href="{{ $examenDesbloqueado ? route('alumno.curso', ['curso' => $curso->id, 'examen' => 1]) : 'javascript:void(0)' }}"
                       @if(!$examenDesbloqueado) aria-disabled="true" @endif
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all border-t border-neutral-100 mt-1
                              {{ $vista === 'examen' ? 'bg-orange-50 border border-orange-200' : '' }}
                              {{ $examenDesbloqueado ? 'hover:bg-neutral-50 cursor-pointer' : 'opacity-50 cursor-not-allowed' }}">
                        <span class="w-6 h-6 shrink-0 rounded-full flex items-center justify-center text-xs font-bold
                                     {{ $aprobado ? 'bg-green-100 text-green-700' : ($examenDesbloqueado ? 'bg-orange-100 text-orange-700' : 'bg-neutral-100 text-neutral-400') }}">
                            {{ $aprobado ? '✓' : ($examenDesbloqueado ? '📝' : '🔒') }}
                        </span>
                        <span class="font-semibold text-neutral-700">Evaluación: {{ $examen->titulo }}</span>
                    </a>
                @endif
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL -->
        <section class="lg:col-span-2 space-y-4">

            @if($vista === 'modulo' && $moduloActual)
                @php $moduloHecho = in_array($moduloActual->id, $completados); @endphp

                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5 space-y-4">
                    <div>
                        <span class="text-xs font-bold text-orange-600 uppercase">Módulo {{ $indiceActual + 1 }} de {{ $totalModulos }}</span>
                        <h2 class="text-xl font-bold text-neutral-900">{{ $moduloActual->titulo }}</h2>
                        @if($moduloActual->descripcion)
                            <p class="text-xs text-neutral-500 mt-1">{{ $moduloActual->descripcion }}</p>
                        @endif
                    </div>

                    @if($moduloActual->video)
                        <video id="video-modulo"
                               class="w-full rounded-2xl bg-black aspect-video"
                               controls controlsList="nodownload" preload="metadata"
                               data-bloquear="{{ ($moduloHecho || !$asignado) ? '0' : '1' }}">
                            <source src="{{ asset($moduloActual->video) }}">
                            Tu navegador no soporta la reproducción de video.
                        </video>
                    @else
                        <div class="w-full aspect-video rounded-2xl bg-neutral-100 border border-dashed border-neutral-300 flex items-center justify-center text-sm text-neutral-400">
                            Este módulo aún no tiene un video cargado.
                        </div>
                    @endif

                    @if($asignado)
                        @if($moduloHecho)
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-bold text-green-700">✓ Módulo completado</span>
                                @if($siguiente)
                                    <a href="{{ route('alumno.curso', ['curso' => $curso->id, 'modulo' => $siguiente->id]) }}" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-bold rounded-xl transition-all">Siguiente módulo →</a>
                                @elseif($examen)
                                    <a href="{{ route('alumno.curso', ['curso' => $curso->id, 'examen' => 1]) }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition-all">Ir a la evaluación →</a>
                                @endif
                            </div>
                        @else
                            <form method="POST" action="{{ route('alumno.modulo.completar', ['curso' => $curso->id, 'modulo' => $moduloActual->id]) }}" class="flex items-center justify-between gap-3">
                                @csrf
                                <p id="aviso-video" class="text-xs text-neutral-400">
                                    @if($moduloActual->video) Termina de ver el video para poder continuar. @endif
                                </p>
                                <button type="submit" id="btn-completar-modulo"
                                        @if($moduloActual->video) disabled @endif
                                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                                    Completar módulo y continuar →
                                </button>
                            </form>
                        @endif
                    @else
                        <p class="text-xs text-neutral-400">Estás viendo una vista previa: el avance solo se registra para colaboradores con el curso asignado.</p>
                    @endif
                </div>

            @elseif($vista === 'examen' && $examen)

                @if($resultado)
                    <div class="rounded-2xl border p-5 space-y-3 {{ $resultado['aprobado'] ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-black">{{ $resultado['aprobado'] ? '¡Aprobaste la evaluación!' : 'No alcanzaste la nota mínima' }}</h3>
                                <p class="text-sm">
                                    Nota: <strong>{{ $resultado['puntaje'] }}/20</strong> ({{ $resultado['correctas'] }} de {{ $resultado['total'] }} respuestas correctas).
                                    Nota mínima: {{ $resultado['nota_minima'] }}.
                                    @unless($resultado['aprobado']) Puedes volver a intentarlo abajo. @endunless
                                </p>
                            </div>

                            @if($resultado['aprobado'])
                                <a href="{{ route('certificado.descargar', $curso->id) }}" 
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs inline-flex items-center gap-2 shadow-md transition-all self-start sm:self-auto">
                                    <i data-lucide="award" class="w-4 h-4"></i> Descargar Certificado
                                </a>
                            @endif
                        </div>
                    </div>
                @elseif($aprobado && $mejor)
                    <div class="rounded-2xl border bg-green-50 border-green-200 text-green-800 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold">✓ Ya aprobaste esta evaluación (mejor nota: <strong>{{ $mejor->puntaje }}/20</strong>). Puedes volver a rendirla si quieres.</p>
                        </div>
                        <a href="{{ route('certificado.descargar', $curso->id) }}" 
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-xs inline-flex items-center gap-2 shadow-md transition-all shrink-0">
                            <i data-lucide="award" class="w-4 h-4"></i> Descargar Certificado
                        </a>
                    </div>
                @endif

                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5 space-y-5">
                    <div>
                        <span class="text-xs font-bold text-orange-600 uppercase">Evaluación</span>
                        <h2 class="text-xl font-bold text-neutral-900">{{ $examen->titulo }}</h2>
                        <p class="text-xs text-neutral-400">Nota mínima para aprobar: {{ $examen->nota_minima }}/20 · {{ $examen->preguntas->count() }} preguntas</p>
                    </div>

                    <form method="POST" action="{{ route('alumno.examen.rendir', $curso->id) }}" class="space-y-5">
                        @csrf

                        @foreach($examen->preguntas as $n => $pregunta)
                            <fieldset class="space-y-2 border border-neutral-100 rounded-xl p-4 bg-neutral-50">
                                <legend class="px-1 text-sm font-bold text-neutral-800">{{ $n + 1 }}. {{ $pregunta->pregunta }}</legend>
                                @foreach($pregunta->respuestas as $respuesta)
                                    <label class="flex items-center gap-2 text-sm text-neutral-700 cursor-pointer">
                                        <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $respuesta->id }}" required class="accent-orange-600">
                                        <span>{{ $respuesta->respuesta }}</span>
                                    </label>
                                @endforeach
                            </fieldset>
                        @endforeach

                        @if($asignado)
                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition-all shadow-md cursor-pointer">
                                    Enviar evaluación
                                </button>
                            </div>
                        @else
                            <p class="text-xs text-neutral-400">Vista previa: solo los colaboradores con el curso asignado pueden enviar la evaluación.</p>
                        @endif
                    </form>
                </div>

            @else
                <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-10 text-center space-y-2">
                    <div class="text-3xl">📭</div>
                    <h3 class="font-bold text-neutral-800">Este curso todavía no tiene contenido</h3>
                    <p class="text-xs text-neutral-400">El equipo de capacitación aún no ha cargado módulos ni evaluación.</p>
                </div>
            @endif

        </section>
    </div>
</div>
@endsection