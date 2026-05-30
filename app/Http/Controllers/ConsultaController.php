<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Charge;

class ConsultaController extends Controller
{
    // 1. MOSTRAR EL BUSCADOR PÚBLICO
    public function index()
    {
        return view('public.consulta');
    }

    // 2. PROCESAR LA BÚSQUEDA POR CARNET (CI)
    public function buscar(Request $request)
    {
        $request->validate(['ci' => 'required|string']);

        // Buscamos al postulante por su CI
        $postulante = Postulante::where('ci', $request->ci)->first();

        if (!$postulante) {
            return back()->with('error', 'El Carnet de Identidad ingresado no se encuentra registrado.');
        }

        return view('public.consulta', compact('postulante'));
    }

    // 3. PASARELA DE STRIPE Y CREACIÓN AUTOMÁTICA DE USUARIO
    public function pagar(Request $request, Postulante $postulante)
    {
        // Si aún no has configurado Stripe, pon una llave de prueba temporal en tu archivo .env:
        // STRIPE_SECRET=sk_test_tu_llave_aqui
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_temporal'));

        try {
            // Realizamos el cobro simulado
            $charge = Charge::create([
                "amount" => 50000, // 500.00 BOB en centavos
                "currency" => "bob",
                "source" => $request->stripeToken,
                "description" => "Pago de matrícula CUP - " . $postulante->nombre
            ]);

            // SI EL PAGO FUE EXITOSO -> CREAMOS EL USUARIO DE FORMA AUTOMÁTICA
            $user = User::where('email', $postulante->correo)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $postulante->nombre,
                    'email' => $postulante->correo,
                    'password' => Hash::make($postulante->ci), // Su CI es su primera contraseña
                ]);
            }

            // Actualizamos al postulante a su estado final
            $postulante->update([
                'estado' => 'inscrito',
                'recibo_pago' => $charge->id,
                'user_id' => $user->id
            ]);

            // Redirigimos a la pantalla de éxito con las credenciales
            return view('public.exito', [
                'nombre' => $postulante->nombre,
                'usuario' => $postulante->correo,
                'contrasena' => $postulante->ci
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}