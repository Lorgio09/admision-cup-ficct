<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        return view('carreras.index', compact('carreras'));
    }

    public function create()
    {
        return view('carreras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:carreras',
            'nombre' => 'required|string|max:255',
            'cupo' => 'required|integer|min:1',
        ]);

        Carrera::create($request->all());

        return redirect()->route('carreras.index')
                         ->with('success', 'Carrera registrada exitosamente.');
    }

    public function edit(Carrera $carrera)
    {
        return view('carreras.edit', compact('carrera'));
    }

    public function update(Request $request, Carrera $carrera)
    {
        $request->validate([
            // En la actualización ignoramos el código actual de la carrera para que no marque error de "ya existe"
            'codigo' => 'required|string|max:20|unique:carreras,codigo,' . $carrera->id,
            'nombre' => 'required|string|max:255',
            'cupo' => 'required|integer|min:1',
        ]);

        $carrera->update($request->all());

        return redirect()->route('carreras.index')
                         ->with('success', 'Datos de la carrera actualizados.');
    }

    public function destroy(Carrera $carrera)
    {
        $carrera->delete();
        return redirect()->route('carreras.index')
                         ->with('success', 'Carrera eliminada del sistema.');
    }
}