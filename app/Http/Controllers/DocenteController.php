<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    public function index()
    {
        // El 'with' carga la relación de usuario y materia en una sola consulta rápida
        $docentes = Docente::with(['user', 'materia'])->get(); 
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {    
        $materias = Materia::all();
        return view('docentes.create', compact('materias')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:docentes',
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:users,email', // El correo va a la tabla de usuarios
            'materia_id' => 'required|exists:materias,id'
        ]);

        // 1. Creamos la cuenta de usuario para que el docente pueda iniciar sesión
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->correo,
            'password' => Hash::make('12345678'), // Contraseña por defecto
            'rol' => 'docente' // Asignamos el rol
        ]);

        // 2. Registramos los datos específicos del docente y lo unimos al usuario
        Docente::create([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'user_id' => $user->id,
            'materia_id' => $request->materia_id
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado y cuenta de acceso creada.');
    }

    public function edit(Docente $docente)
    {
        //Cargamos las materias para que el select de la vista de edición no esté vacío
        $materias = Materia::all();
        return view('docentes.edit', compact('docente', 'materias'));
    }

    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:docentes,ci,' . $docente->id,
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:users,email,' . $docente->user_id,
            'materia_id' => 'required|exists:materias,id' // CORRECCIÓN: Agregada regla de validación real
        ]);

        // 1. Actualizamos su cuenta de usuario
        if ($docente->user) {
            $docente->user->update([
                'name' => $request->nombre,
                'email' => $request->correo,
            ]);
        }

        // 2. Actualizamos sus datos de docente
        $docente->update([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo' => $request->correo, // CORRECCIÓN: Guardamos el correo editado en la tabla de docentes
            'materia_id' => $request->materia_id // CORRECCIÓN: Guardamos la nueva materia asignada
        ]);

        return redirect()->route('docentes.index')->with('success', 'Datos del docente actualizados.');
    }

    public function destroy(Docente $docente)
    {
        // Al eliminar al docente, también eliminamos su cuenta de acceso del sistema
        if ($docente->user) {
            $docente->user->delete(); 
        }
        
        $docente->delete();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado del sistema.');
    }
}