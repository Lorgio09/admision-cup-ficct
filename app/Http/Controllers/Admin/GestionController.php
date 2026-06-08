<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Gestion;
use Illuminate\Http\Request;

class GestionController extends Controller
{
    public function create()
    {
        // Traemos las 4 carreras (Sistemas, Informática, Redes y Robótica)
        $carreras = Carrera::all();
        return view('admin.gestiones.create', compact('carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'cupos' => 'required|array',
            'cupos.*' => 'required|integer|min:0', 
        ]);

        // Desactivamos los anteriores para que solo uno esté activo
        Gestion::where('is_active', true)->update(['is_active' => false]);

        // Creamos el nuevo semestre
        $gestion = Gestion::create([
            'nombre' => $request->nombre,
            'is_active' => true
        ]);

        // Preparamos los datos para la tabla pivote carrera_gestion
        $syncData = [];
        foreach ($request->cupos as $carrera_id => $cupo) {
            $syncData[$carrera_id] = ['cupo_maximo' => $cupo];
        }

        // Guardamos todos los cupos de las 4 carreras simultáneamente
        $gestion->carreras()->sync($syncData);

        return redirect()->route('dashboard')->with('status', '¡Semestre ' . $gestion->nombre . ' habilitado con sus respectivos cupos!');
    }

    // ==========================================
    // NUEVAS FUNCIONES PARA EDITAR
    // ==========================================

    public function edit(Gestion $gestion)
    {
        // Cargamos todas las carreras
        $carreras = Carrera::all();
        
        // Cargamos la relación de la gestión con sus carreras para extraer el "cupo_maximo" actual
        $gestion->load('carreras'); 
        
        return view('admin.gestiones.edit', compact('gestion', 'carreras'));
    }

    public function update(Request $request, Gestion $gestion)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'cupos' => 'required|array',
            'cupos.*' => 'required|integer|min:0',
        ]);

        // 1. Actualizamos el nombre de la gestión
        $gestion->update([
            'nombre' => $request->nombre,
        ]);

        // 2. Preparamos el arreglo para la tabla pivote, tal cual lo haces en store()
        $syncData = [];
        foreach ($request->cupos as $carrera_id => $cupo) {
            $syncData[$carrera_id] = ['cupo_maximo' => $cupo];
        }

        // 3. Sincronizamos (esto actualiza automáticamente los cupos de esta gestión)
        $gestion->carreras()->sync($syncData);

        return redirect()->route('dashboard')->with('status', '¡Gestión y cupos actualizados correctamente!');
    }


    // ==========================================
    // MOTOR DE ASIGNACIÓN MERITOCRÁTICA
    // ==========================================
    public function procesarAdmisiones(Gestion $gestion)
    {
        // 1. Traer a los alumnos APROBADOS (nota >= 60) de ESTA gestión, ordenados del mejor al peor promedio
        $postulantes = \App\Models\Postulante::whereHas('grupo', function($q) use ($gestion) {
            $q->where('gestion_id', $gestion->id);
        })
        ->where('estado', 'APROBADO')
        ->orderByDesc('promedio')
        ->get();

        // 2. Cargar los cupos actuales desde la tabla pivote para saber cuántos espacios hay
        $gestion->load('carreras');
        $cuposDisponibles = [];
        foreach ($gestion->carreras as $carrera) {
            $cuposDisponibles[$carrera->id] = $carrera->pivot->cupo_maximo;
        }

        // 3. El Bucle de Asignación Justa
        foreach ($postulantes as $postulante) {
            $asignado = null;

            // Intentamos en su Primera Opción
            $opcion1 = $postulante->carrera_primera_opcion_id;
            if (isset($cuposDisponibles[$opcion1]) && $cuposDisponibles[$opcion1] > 0) {
                $asignado = $opcion1;
                $cuposDisponibles[$opcion1]--; // Restamos 1 cupo temporalmente en memoria
            } 
            // Si no alcanzó, intentamos en su Segunda Opción
            else {
                $opcion2 = $postulante->carrera_segunda_opcion_id;
                if (isset($cuposDisponibles[$opcion2]) && $cuposDisponibles[$opcion2] > 0) {
                    $asignado = $opcion2;
                    $cuposDisponibles[$opcion2]--;
                }
            }

            // 4. Guardamos el destino final del estudiante
            if ($asignado) {
                $postulante->update([
                    'carrera_admitida_id' => $asignado,
                    'estado' => 'ADMITIDO' // Cambiamos el estado para saber que ya se le dio un cupo
                ]);
            } else {
                $postulante->update([
                    'estado' => 'APROBADO_SIN_CUPO' // Triste realidad: sacó más de 60 pero se acabaron los cupos
                ]);
            }
        }

        // 5. Actualizamos los cupos sobrantes en la tabla pivote de la base de datos
        $syncData = [];
        foreach ($cuposDisponibles as $carrera_id => $cupoRestante) {
            $syncData[$carrera_id] = ['cupo_maximo' => $cupoRestante];
        }
        $gestion->carreras()->sync($syncData);

        return redirect()->back()->with('status', '✅ Admisiones procesadas correctamente. Los cupos han sido asignados por orden de mérito.');
    }
}