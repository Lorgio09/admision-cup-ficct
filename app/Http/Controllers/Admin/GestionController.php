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
        // 1. Validamos que nos envíen el nombre y el arreglo de cupos
        $request->validate([
            'nombre' => 'required|string|max:50',
            'cupos' => 'required|array',
            'cupos.*' => 'required|integer|min:0', // El cupo no puede ser negativo
        ]);

        // 2. Por regla de negocio, si abrimos un nuevo semestre, 
        // desactivamos los anteriores para que solo uno esté activo
        Gestion::where('is_active', true)->update(['is_active' => false]);

        // 3. Creamos el nuevo semestre
        $gestion = Gestion::create([
            'nombre' => $request->nombre,
            'is_active' => true
        ]);

        // 4. Preparamos los datos para la tabla pivote carrera_gestion
        $syncData = [];
        foreach ($request->cupos as $carrera_id => $cupo) {
            $syncData[$carrera_id] = ['cupo_maximo' => $cupo];
        }

        // 5. Guardamos todos los cupos de las 4 carreras simultáneamente
        $gestion->carreras()->sync($syncData);

        return redirect()->route('dashboard')->with('status', '¡Semestre ' . $gestion->nombre . ' habilitado con sus respectivos cupos!');
    }
}