<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Gestion;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluacion;

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
            // EL ADMIN LO VE TODO: Cargamos todas las asignaciones del semestre
            $asignaciones = Asignacion::with(['grupo', 'materia', 'docente'])
                ->where('gestion_id', $gestionActiva->id)
                ->orderBy('grupo_id')
                ->get();
                
        } elseif ($user->rol === 'docente') {
            // EL DOCENTE VE SOLO LO SUYO: Buscamos su perfil por correo
            $docente = Docente::where('correo', $user->email)->first();
            
            if (!$docente) {
                return back()->with('error', 'Tu cuenta no está vinculada a un perfil de docente.');
            }

            // Traemos solo las asignaciones donde él es el titular
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

        // 1. Capa de Seguridad: Evitar que un docente califique grupos ajenos por URL
        if ($user->rol === 'docente') {
            $docente = Docente::where('correo', $user->email)->first();
            if (!$docente || $asignacion->docente_id !== $docente->id) {
                abort(403, 'Acceso denegado: No eres el docente titular de esta materia.');
            }
        }

        // 2. Traer la lista de alumnos del grupo correspondiente
        $postulantes = \App\Models\Postulante::where('grupo_id', $asignacion->grupo_id)
                                             ->orderBy('nombre')
                                             ->get();

        // 3. Buscar si ya hay notas guardadas de estos alumnos en esta materia
        // Usamos keyBy('postulante_id') para que en la vista sea fácil cruzar los datos
        $evaluaciones = Evaluacion::where('materia_id', $asignacion->materia_id)
                                  ->whereIn('postulante_id', $postulantes->pluck('id'))
                                  ->get()
                                  ->keyBy('postulante_id');

        return view('calificaciones.planilla', compact('asignacion', 'postulantes', 'evaluaciones'));
    }

    public function guardar(Request $request, Asignacion $asignacion)
    {
        // Validamos que las notas sean números del 0 al 100
        $request->validate([
            'notas' => 'required|array',
            'notas.*' => 'nullable|numeric|min:0|max:100',
        ]);

        // Guardamos o actualizamos la nota de cada alumno de forma masiva
        foreach ($request->notas as $postulante_id => $nota) {
            if ($nota !== null) { // Solo guardamos si el docente escribió algo
                Evaluacion::updateOrCreate(
                    [
                        'postulante_id' => $postulante_id,
                        'materia_id' => $asignacion->materia_id,
                    ],
                    [
                        'nota' => $nota
                    ]
                );
            }
        }

        return redirect()->route('calificaciones.index')->with('success', '✅ Planilla de ' . $asignacion->materia->nombre . ' guardada con éxito.');
    }
}
