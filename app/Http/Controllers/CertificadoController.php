<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\IntentoExamen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class CertificadoController extends Controller
{
    public function descargar($curso_id)
    {
        // Consulta directa a la base de datos para forzar la carga del campo DNI
        $user = User::findOrFail(Auth::id());
        $curso = Curso::findOrFail($curso_id);

        // Verificar que el alumno haya aprobado el examen de este curso
        $intentoAprobado = IntentoExamen::where('user_id', $user->id)
            ->whereHas('examen', function ($q) use ($curso_id) {
                $q->where('curso_id', $curso_id);
            })
            ->where('aprobado', true)
            ->latest()
            ->first();

        if (!$intentoAprobado) {
            return redirect()->back()->with('warning', 'Aún no has aprobado la evaluación de este curso para obtener tu certificado.');
        }

        // Formatear la fecha en español (Ej: 14 DE AGOSTO DE 2026)
        Carbon::setLocale('es');
        $fechaEmision = Carbon::parse($intentoAprobado->created_at)->translatedFormat('d \d\e F \d\e Y');

        // Garantizar lectura limpia del DNI
        $dniAlumno = trim((string)($user->dni ?? ''));

        // Código único de certificado (Ej: ANKA-0007-007)
        $codigoCertificado = 'ANKA-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($curso->id, 3, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('certificados.pdf', compact('user', 'curso', 'fechaEmision', 'codigoCertificado', 'dniAlumno'))
            ->setPaper('a4', 'landscape')
            ->setOption(['isRemoteEnabled' => true]);

        return $pdf->download('Certificado_' . Str::slug($curso->titulo) . '_' . ($dniAlumno ?: 'doc') . '.pdf');
    }
}