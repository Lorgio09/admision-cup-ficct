<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::all();
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:docentes',
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:users,email' // El correo va a la tabla de usuarios
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
            'user_id' => $user->id
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado y cuenta de acceso creada.');
    }

    public function edit(Docente $docente)
    {
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'ci' => 'required|string|max:15|unique:docentes,ci,' . $docente->id,
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:users,email,' . $docente->user_id
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