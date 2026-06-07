<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Postulante; 
use Illuminate\Http\Request;

class SeleccionGrupoController extends Controller
{
    public function index()
    {
        $gestionActiva = \App\Models\Gestion::where('is_active', true)->first();
        // 1. Buscamos solo los grupos que tengan MENOS de 70 postulantes
        $gruposDisponibles = Grupo::where('gestion_id', $gestionActiva->id)
            ->withCount('postulantes')
            ->has('postulantes', '<', 70)
            ->get();

        return view('estudiante.seleccionar-grupo', compact('gruposDisponibles'));
    }

    public function store(Request $request)
    {
        // 1. Validamos que el grupo enviado exista
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id'
        ]);

        $grupo = Grupo::withCount('postulantes')->findOrFail($request->grupo_id);

        // 2. Doble validación de seguridad
        if ($grupo->postulantes_count >= 70) {
            return back()->with('error', 'Lo sentimos, este grupo se acaba de llenar. Por favor elige otro.');
        }

        // 3. Le asignamos el grupo al postulante
        // Como tu relación es con Postulante, buscamos el registro del postulante 
        // asociado al correo del usuario que inició sesión
        $user = $request->user();
        $postulante = Postulante::where('correo', $user->email)->first();
        
        if ($postulante) {
            $postulante->grupo_id = $grupo->id;
            $postulante->save();
        }

        // 4. Lo mandamos a su panel principal
        return redirect()->route('dashboard')->with('status', '¡Felicidades! Te has inscrito exitosamente en el grupo.');
    }
}