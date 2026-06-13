<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::all();
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        return view('materias.create');
    }

    public function store(Request $request)
    {
        // Validamos que tanto el código como el nombre sean obligatorios y no se repitan
        $request->validate([
            'codigo' => 'required|string|max:20|unique:materias',
            'nombre' => 'required|string|max:255|unique:materias',
        ]);

        Materia::create($request->all());

        return redirect()->route('materias.index')
                         ->with('success', 'Materia registrada exitosamente.');
    }

    public function edit(Materia $materia)
    {
        return view('materias.edit', compact('materia'));
    }

    public function update(Request $request, Materia $materia)
    {
        // Al actualizar, ignoramos el ID de la materia actual para que no dé error de "código ya existe"
        $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo,' . $materia->id,
            'nombre' => 'required|string|max:255|unique:materias,nombre,' . $materia->id,
        ]);

        $materia->update($request->all());

        return redirect()->route('materias.index')
                         ->with('success', 'Materia actualizada exitosamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        
        return redirect()->route('materias.index')
                         ->with('success', 'Materia eliminada del sistema.');
    }
}