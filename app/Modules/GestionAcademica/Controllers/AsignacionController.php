<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Gestion;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{

    public function store(Request $request)
    {
        $gestionActiva = Gestion::where('is_active', true)->first();

        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'required|exists:docentes,id',
        ]);

        // Verificamos que el grupo no tenga ya un profesor para esa materia específica
        $existe = Asignacion::where('grupo_id', $request->grupo_id)
                            ->where('materia_id', $request->materia_id)
                            ->where('gestion_id', $gestionActiva->id)
                            ->first();

        if ($existe) {
            return back()->with('error', '⚠️ Error: Este grupo ya tiene un profesor asignado para esa materia.');
        }

        Asignacion::create([
            'grupo_id' => $request->grupo_id,
            'materia_id' => $request->materia_id,
            'docente_id' => $request->docente_id,
            'gestion_id' => $gestionActiva->id,
        ]);

        return back()->with('success', '✅ Asignación guardada con éxito.');
    }

    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();
        return back()->with('success', '🗑️ Asignación eliminada correctamente.');
    }
}