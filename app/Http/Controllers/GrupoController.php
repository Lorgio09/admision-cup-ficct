<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    // 1. MOSTRAR EL PANEL
    public function index()
    {
        $totalInscritos = Postulante::where('estado', 'inscrito')->count();
        $cantidadGruposRequeridos = ceil($totalInscritos / 70);
        
        // Traemos las aulas con DB facade para evitar errores si aún no creaste el Modelo Aula
        $aulas = DB::table('aulas')->get(); 
        
        $grupos = Grupo::with('postulantes')->get();
        $pendientesDeAsignar = Postulante::where('estado', 'inscrito')->whereNull('grupo_id')->count();

        return view('grupos.index', compact('totalInscritos', 'cantidadGruposRequeridos', 'grupos', 'pendientesDeAsignar', 'aulas'));
    }

    // 2. CREAR GRUPO MANUALMENTE (NUEVO)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'turno' => 'required|in:Mañana,Tarde,Noche',
            'aula_id' => 'nullable|exists:aulas,id'
        ]);

        Grupo::create([
            'nombre' => $request->nombre,
            'turno' => $request->turno,
            'aula_id' => $request->aula_id,
        ]);

        return back()->with('success', 'Grupo creado exitosamente. Ahora puedes asignarle alumnos.');
    }

    // 3. LLENAR LOS GRUPOS EXISTENTES (INTELIGENTE)
    public function generar()
    {
        $alumnosSinGrupo = Postulante::where('estado', 'inscrito')->whereNull('grupo_id')->get();

        if ($alumnosSinGrupo->isEmpty()) {
            return back()->with('error', 'No hay alumnos pendientes para asignar.');
        }

        // Buscamos los grupos que tú creaste y que tengan MENOS de 70 alumnos
        $gruposConEspacio = Grupo::withCount('postulantes')
                                 ->having('postulantes_count', '<', 70)
                                 ->get();

        if ($gruposConEspacio->isEmpty()) {
            return back()->with('error', 'No hay grupos con espacio disponible. Por favor, crea un nuevo grupo manualmente primero.');
        }

        $alumnosAsignados = 0;

        foreach ($gruposConEspacio as $grupo) {
            // Calculamos cuántas sillas vacías quedan en este grupo
            $espacioDisponible = 70 - $grupo->postulantes_count;
            
            // Cortamos de la lista principal solo la cantidad de alumnos que caben aquí
            $alumnosParaEsteGrupo = $alumnosSinGrupo->splice(0, $espacioDisponible);
            
            foreach ($alumnosParaEsteGrupo as $alumno) {
                $alumno->update(['grupo_id' => $grupo->id]);
                $alumnosAsignados++;
            }

            // Si ya no quedan alumnos en la lista de espera, detenemos el proceso
            if ($alumnosSinGrupo->isEmpty()) {
                break;
            }
        }

        // Si se llenaron los grupos pero aún quedaron alumnos sin asignar:
        if ($alumnosSinGrupo->isNotEmpty()) {
            return back()->with('success', "Se asignaron $alumnosAsignados alumnos, pero faltó espacio para " . $alumnosSinGrupo->count() . " postulantes. Por favor, crea más grupos.");
        }

        return back()->with('success', "¡Se asignaron los $alumnosAsignados alumnos a tus grupos de forma exitosa!");
    }

    // 4. ELIMINAR GRUPO
    public function destroy(Grupo $grupo)
    {
        // Guardamos el nombre para el mensaje
        $nombreGrupo = $grupo->nombre;
        
        // Eliminamos el grupo físico. 
        // Gracias a tu migración, los alumnos no se borran, solo vuelven a quedar "pendientes"
        $grupo->delete();

        return back()->with('success', "El $nombreGrupo fue eliminado. Sus estudiantes ahora están pendientes de asignación.");
    }
}