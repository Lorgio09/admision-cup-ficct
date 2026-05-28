<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    // ==================================================
    // RUTAS DEL ADMINISTRADOR (Privadas)
    // ==================================================

    // 1. LISTAR POSTULANTES
    public function index()
    {
        // Traemos todos los postulantes con sus dos carreras relacionadas
        $postulantes = Postulante::with(['primeraOpcion', 'segundaOpcion'])->get();
        return view('postulantes.index', compact('postulantes'));
    }

    // 4. MOSTRAR FORMULARIO DE EDICIÓN
    public function edit(Postulante $postulante)
    {
        $carreras = Carrera::all();
        return view('postulantes.edit', compact('postulante', 'carreras'));
    }

    // 5. ACTUALIZAR LOS DATOS MODIFICADOS
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'ci' => 'required|unique:postulantes,ci,' . $postulante->id,
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:postulantes,correo,' . $postulante->id,
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'carrera_primera_opcion_id' => 'required|exists:carreras,id',
            'carrera_segunda_opcion_id' => 'required|exists:carreras,id|different:carrera_primera_opcion_id',
        ], [
            'carrera_segunda_opcion_id.different' => 'La segunda opción debe ser diferente a la primera.'
        ]);

        $postulante->update($request->all());

        return redirect()->route('postulantes.index')->with('success', 'Datos del postulante actualizados.');
    }

    // 6. ELIMINAR REGISTRO
    public function destroy(Postulante $postulante)
    {
        $postulante->delete();
        return redirect()->route('postulantes.index')->with('success', 'Postulante eliminado del sistema.');
    }

    // ==================================================
    // RUTAS PÚBLICAS (Flujo de Inscripción y Pago)
    // ==================================================

    // 2. MOSTRAR FORMULARIO DE REGISTRO
    public function create()
    {
        $carreras = Carrera::all();
        return view('postulantes.create', compact('carreras'));
    }

    // 3. GUARDAR EL REGISTRO EN LA MEMORIA (SESIÓN)
    public function store(Request $request)
    {
        // Validamos los datos
        $datosValidados = $request->validate([
            'ci' => 'required|unique:postulantes,ci',
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:postulantes,correo',
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'carrera_primera_opcion_id' => 'required|exists:carreras,id',
            'carrera_segunda_opcion_id' => 'required|exists:carreras,id|different:carrera_primera_opcion_id',
            'certificado_bachiller' => 'accepted'
        ], [
            'ci.unique' => 'Este CI ya está registrado en el sistema.',
            'correo.unique' => 'Este correo ya está en uso.',
            'carrera_segunda_opcion_id.different' => 'La segunda opción de carrera no puede ser igual a la primera.'
        ]);

        // En vez de crear el registro en la BD, lo guardamos en la Sesión temporal
        session()->put('datos_temporales_postulante', $datosValidados);

        // Lo mandamos directamente a pagar
        return redirect()->route('postulantes.checkout');
    }

    // MOSTRAR PANTALLA DE PAGO
    public function checkout()
    {
        // Si alguien intenta entrar a /pago por URL sin llenar el formulario, lo rebotamos
        if (!session()->has('datos_temporales_postulante')) {
            return redirect()->route('postulantes.create');
        }

        // Le pasamos los datos temporales a la vista para mostrar su nombre
        $datos = session('datos_temporales_postulante');
        
        return view('postulantes.checkout', compact('datos'));
    }

    // PROCESAR EL COBRO CON STRIPE Y GUARDAR EN LA BD
    public function procesarPago(Request $request)
    {
        if (!session()->has('datos_temporales_postulante')) {
            return redirect()->route('postulantes.create');
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $datos = session('datos_temporales_postulante');

            // 1. Intentamos hacer el cobro real
            $charge = \Stripe\Charge::create([
                'amount' => 700 * 100, 
                'currency' => 'bob', 
                'description' => 'Pago de Inscripción CUP - ' . $datos['nombre'],
                'source' => $request->stripeToken, 
            ]);

            // 2. ¡COBRO EXITOSO! Ahora sí, guardamos en la base de datos de forma definitiva
            Postulante::create([
                'ci' => $datos['ci'],
                'nombre' => $datos['nombre'],
                'correo' => $datos['correo'],
                'sexo' => $datos['sexo'],
                'telefono' => $datos['telefono'],
                'direccion' => $datos['direccion'],
                'carrera_primera_opcion_id' => $datos['carrera_primera_opcion_id'],
                'carrera_segunda_opcion_id' => $datos['carrera_segunda_opcion_id'],
                'certificado_bachiller' => true,
                'estado' => 'pendiente', // Ya pagó, directo a la lista de revisión del Admin
                'recibo_pago' => $charge->id
            ]);

            // 3. Borramos los datos temporales y redirigimos con el mensaje personalizado
            session()->forget('datos_temporales_postulante');

            $mensaje = '¡Inscripción y pago procesados con éxito! Tu solicitud está en revisión académica. Una vez que el Administrador apruebe tus documentos, podrás iniciar sesión usando tu correo electrónico y tu CI (' . $datos['ci'] . ') como contraseña. Código de recibo: ' . $charge->id;

            return redirect('/')->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar la tarjeta: ' . $e->getMessage()]);
        }
    }
}