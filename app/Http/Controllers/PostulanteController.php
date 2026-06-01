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
    public function index(Request $request)
    {
        // 1. Capturamos lo que el usuario escribió en el buscador (si es que escribió algo)
        $search = $request->input('search');

        // 2. Hacemos la consulta a la base de datos
        $postulantes = Postulante::when($search, function ($query, $search) {
            // Nota: Como vi en tus capturas que usas PostgreSQL, usamos 'ilike' para que no importe si escriben en mayúsculas o minúsculas.
            return $query->where('nombre', 'ilike', "%{$search}%")
                         ->orWhere('ci', 'ilike', "%{$search}%");
        })
        ->orderBy('created_at', 'desc') // Ordenamos para que los más nuevos salgan primero
        ->paginate(10); // Paginamos de 10 en 10 (¡Súper profesional!)

        // 3. Mandamos los datos a la vista, incluyendo el término de búsqueda para mantenerlo en el input
        return view('postulantes.index', compact('postulantes', 'search'));
    }

    // MOSTRAR EL FORMULARIO DE EDICIÓN (ADMINISTRADOR)
    public function edit(Postulante $postulante)
    {
        // Traemos todas las carreras para llenar los selectores
        $carreras = \App\Models\Carrera::all();
        
        return view('postulantes.edit', compact('postulante', 'carreras'));
    }

    // 5. ACTUALIZAR LOS DATOS MODIFICADOS
    public function update(Request $request, Postulante $postulante)
    {
        // Validamos los datos. 
        // NOTA CLAVE: En 'ci' y 'correo' le decimos a Laravel que ignore el ID actual
        // para que no lance error de "correo ya registrado" si el usuario no lo cambió.
        $request->validate([
            'ci' => 'required|string|unique:postulantes,ci,' . $postulante->id,
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:postulantes,correo,' . $postulante->id,
            'sexo' => 'required|in:M,F',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ciudad_nacimiento' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'colegio_procedencia' => 'required|string|max:150',
            'ciudad_residencia' => 'required|string|max:100',
            'carrera_primera_opcion_id' => 'required|exists:carreras,id',
            'carrera_segunda_opcion_id' => 'required|exists:carreras,id|different:carrera_primera_opcion_id',
            'estado' => 'required|string'
        ]);

        // Actualizamos los datos masivamente
        $postulante->update($request->all());

        // Redirigimos a la lista con mensaje verde
        return redirect()->route('postulantes.index')->with('success', '¡Datos del postulante actualizados correctamente!');
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
        // 1. Validaciones (Mantenlas tal cual las tienes)
        $request->validate([
            'ci' => 'required|string|unique:postulantes',
            'nombre' => 'required|string|max:255',
            // ... (tus otras validaciones)
        ]);

        // 2. Guardamos físicamente al postulante de inmediato
        Postulante::create([
            'ci' => $request->ci,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'sexo' => $request->sexo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'colegio_procedencia' => $request->colegio_procedencia,
            'ciudad_residencia' => $request->ciudad_residencia,
            'carrera_primera_opcion_id' => $request->carrera_primera_opcion_id,
            'carrera_segunda_opcion_id' => $request->carrera_segunda_opcion_id,
            'certificado_bachiller' => true, // O lo que uses para validar el archivo
            'estado' => 'en_revision', // <--- NUEVO ESTADO INICIAL
            'recibo_pago' => null, // Aún no paga
        ]);

        // 3. Redirigimos al inicio con un mensaje de éxito
        // 3. Redirigimos a la pantalla de Inicio de Sesión (Login)
        return redirect('/')->with('status', '¡Registro completado! Tus datos están siendo revisados...');
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
                'recibo_pago' => $charge->id,
                'ciudad_nacimiento' => $datos['ciudad_nacimiento'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'colegio_procedencia' => $datos['colegio_procedencia'],
                'ciudad_residencia' => $datos['ciudad_residencia'],
            ]);

            // 3. Borramos los datos temporales y redirigimos con el mensaje personalizado
            session()->forget('datos_temporales_postulante');

            $mensaje = '¡Inscripción y pago procesados con éxito! Tu solicitud está en revisión académica. Una vez que el Administrador apruebe tus documentos, podrás iniciar sesión usando tu correo electrónico y tu CI (' . $datos['ci'] . ') como contraseña. Código de recibo: ' . $charge->id;

            return redirect('/')->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar la tarjeta: ' . $e->getMessage()]);
        }
    }

    // HABILITAR PASARELA DE PAGO AL ESTUDIANTE
    public function aprobar(Postulante $postulante)
    {
        // Cambiamos el estado para que el alumno pueda pagar
        $postulante->update([
            'estado' => 'pendiente_pago'
        ]);

        return redirect()->route('postulantes.index')->with('success', '¡Documentos aprobados! El postulante ' . $postulante->nombre . ' ya puede acceder a la pasarela de pago.');
    }

    // MOSTRAR PANTALLA PARA INGRESAR NOTAS
    public function evaluar(Postulante $postulante)
    {
        // Traemos las materias disponibles (Computación, Matemáticas, Inglés, Física)
        $materias = \App\Models\Materia::all();
        return view('postulantes.evaluar', compact('postulante', 'materias'));
    }

    // ALGORITMO DE CALIFICACIÓN Y ASIGNACIÓN DE CUPOS
    public function calificar(Request $request, Postulante $postulante)
    {
        // 1. Regla de Negocio: Validar exactamente 3 materias distintas y notas entre 0 y 100
        $request->validate([
            'materias' => 'required|array|size:3',
            'materias.*' => 'required|exists:materias,id|distinct',
            'notas' => 'required|array|size:3',
            'notas.*' => 'required|numeric|min:0|max:100',
        ], [
            'materias.*.distinct' => 'No puedes seleccionar la misma materia dos veces.',
            'notas.*.max' => 'La nota máxima permitida es 100.'
        ]);

        $suma = 0;

        // 2. Guardar las 3 notas en la tabla 'evaluaciones'
        for ($i = 0; $i < 3; $i++) {
            \App\Models\Evaluacion::updateOrCreate(
                ['postulante_id' => $postulante->id, 'materia_id' => $request->materias[$i]],
                ['nota' => $request->notas[$i]]
            );
            $suma += $request->notas[$i];
        }

        // 3. Regla de Negocio: Calcular el promedio final
        $promedio = round($suma / 3, 2);
        $estado_final = 'REPROBADO';
        $carrera_admitida = null;

        // 4. Regla de Negocio: Estado y asignación de cupos
        if ($promedio >= 60) {
            $estado_final = 'APROBADO';
            
            $carrera1 = \App\Models\Carrera::find($postulante->carrera_primera_opcion_id);
            $carrera2 = \App\Models\Carrera::find($postulante->carrera_segunda_opcion_id);

            // Verificamos si hay cupo en su PRIMERA opción
            if ($carrera1 && $carrera1->cupos > 0) {
                $carrera1->decrement('cupos'); // Restamos 1 cupo a la carrera
                $carrera_admitida = $carrera1->id;
            } 
            // Si la primera se llenó, verificamos la SEGUNDA opción
            elseif ($carrera2 && $carrera2->cupos > 0) {
                $carrera2->decrement('cupos'); // Restamos 1 cupo
                $carrera_admitida = $carrera2->id;
            } else {
                // Aprobó, pero lamentablemente ambas carreras están llenas
                $estado_final = 'APROBADO_SIN_CUPO';
            }
        }

        // 5. Actualizamos el registro del postulante
        $postulante->update([
            'promedio' => $promedio,
            'estado' => $estado_final,
            'carrera_admitida_id' => $carrera_admitida
        ]);

        return redirect()->route('postulantes.index')->with('success', '¡Exámenes registrados! Promedio: ' . $promedio . '. Estado: ' . $estado_final);
    }
}