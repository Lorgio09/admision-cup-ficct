<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Gestion;
use App\Models\Docente;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function index()
    {
        $gestionActiva = Gestion::where('is_active', true)->first();
        $user = Auth::user();

        // Si no hay semestre activo, mandamos las variables vacías
        if (!$gestionActiva) {
            return view('calificaciones.index', ['asignaciones' => collect(), 'gestionActiva' => null]);
        }

        if ($user->rol === 'admin') {
            // EL ADMIN LO VE TODO
            $asignaciones = Asignacion::with(['grupo', 'materia', 'docente'])
                ->where('gestion_id', $gestionActiva->id)
                ->orderBy('grupo_id')
                ->get();
                
        } elseif ($user->rol === 'docente') {
            // EL DOCENTE VE SOLO LO SUYO
            $docente = Docente::where('correo', $user->email)->first();
            
            if (!$docente) {
                return back()->with('error', 'Tu cuenta no está vinculada a un perfil de docente.');
            }

            $asignaciones = Asignacion::with(['grupo', 'materia'])
                ->where('gestion_id', $gestionActiva->id)
                ->where('docente_id', $docente->id)
                ->get();
        } else {
            abort(403, 'Acceso no autorizado');
        }

        return view('calificaciones.index', compact('asignaciones', 'gestionActiva'));
    }

    public function planilla(Asignacion $asignacion)
    {
        $user = Auth::user();

        // 1. Capa de Seguridad
        if ($user->rol === 'docente') {
            $docente = Docente::where('correo', $user->email)->first();
            if (!$docente || $asignacion->docente_id !== $docente->id) {
                abort(403, 'Acceso denegado: No eres el docente titular de esta materia.');
            }
        }

        // 2. Traer la lista de alumnos
        $postulantes = \App\Models\Postulante::where('grupo_id', $asignacion->grupo_id)
                                             ->orderBy('nombre')
                                             ->get();

        // 3. Buscar si ya hay notas guardadas
        $evaluaciones = Evaluacion::where('materia_id', $asignacion->materia_id)
                                  ->whereIn('postulante_id', $postulantes->pluck('id'))
                                  ->get()
                                  ->keyBy('postulante_id');

        return view('calificaciones.planilla', compact('asignacion', 'postulantes', 'evaluaciones'));
    }

    public function guardar(Request $request, Asignacion $asignacion)
    {
        // Validamos las 3 columnas de notas
        $request->validate([
            'nota1' => 'array', 'nota1.*' => 'nullable|numeric|min:0|max:100',
            'nota2' => 'array', 'nota2.*' => 'nullable|numeric|min:0|max:100',
            'nota3' => 'array', 'nota3.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Guardamos masivamente las notas
        // Usamos nota1 como pivote para saber qué alumnos estamos procesando
        if ($request->has('nota1')) {
            foreach ($request->nota1 as $postulante_id => $n1) {
                $n2 = $request->nota2[$postulante_id] ?? null;
                $n3 = $request->nota3[$postulante_id] ?? null;

                // Solo guardamos si hay al menos una nota registrada
                if ($n1 !== null || $n2 !== null || $n3 !== null) { 
                    Evaluacion::updateOrCreate(
                        [
                            'postulante_id' => $postulante_id,
                            'materia_id' => $asignacion->materia_id,
                        ],
                        [
                            'nota1' => $n1,
                            'nota2' => $n2,
                            'nota3' => $n3
                        ]
                    );

                    // Revisamos si con este guardado el alumno ya terminó el CUP
                    $this->verificarYCalcularPromedioFinal($postulante_id);
                }
            }
        }

        return redirect()->route('calificaciones.index')->with('success', '✅ Planilla de ' . $asignacion->materia->nombre . ' guardada con éxito.');
    }

    /**
     * MOTOR DE CALIFICACIÓN BÁSICA (Solo evalúa si Aprobó o Reprobó)
     */
    private function verificarYCalcularPromedioFinal($postulante_id)
    {
        $postulante = \App\Models\Postulante::with('grupo.asignaciones')->find($postulante_id);
        
        // Cuántas materias se le exigen en su grupo
        $totalMateriasExigidas = $postulante->grupo->asignaciones->count();
        $evaluaciones = Evaluacion::where('postulante_id', $postulante_id)->get();

        $materiasCompletadas = 0;
        $sumaPromediosMaterias = 0;

        foreach ($evaluaciones as $ev) {
            // Verificamos si en esta materia ya tiene sus 3 notas llenas
            if ($ev->nota1 !== null && $ev->nota2 !== null && $ev->nota3 !== null) {
                $materiasCompletadas++;
                
                // Promedio de esta materia
                $promedioMateria = ($ev->nota1 + $ev->nota2 + $ev->nota3) / 3;
                $sumaPromediosMaterias += $promedioMateria;
            }
        }

        // Si ya completó TODO
        if ($materiasCompletadas > 0 && $materiasCompletadas == $totalMateriasExigidas) {
            
            $promedioFinal = round($sumaPromediosMaterias / $totalMateriasExigidas, 2);
            $estado_final = $promedioFinal >= 60 ? 'APROBADO' : 'REPROBADO';

            // Guardamos solo el promedio y el estado. El Admin se encarga de los cupos luego.
            $postulante->update([
                'promedio' => $promedioFinal,
                'estado' => $estado_final,
            ]);
        }
    }
}