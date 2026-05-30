<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="font-bold text-2xl text-gray-800">Seguimiento de Admisión CUP</h2>
        <p class="text-gray-600 text-sm mt-1">Ingresa tu Carnet de Identidad para consultar tu estado o realizar tu pago.</p>
    </div>

    <form method="POST" action="{{ route('consulta.buscar') }}" class="mb-8 border-b pb-8">
        @csrf
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <label for="ci" class="block text-sm font-medium text-gray-700">Carnet de Identidad (CI)</label>
                <input id="ci" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" type="text" name="ci" required autofocus placeholder="Ej: 1234567" />
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-md transition">
                    Consultar
                </button>
            </div>
        </div>
    </form>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($postulante))
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Hola, {{ $postulante->nombre }}</h3>
            
            <div class="mt-4">
                @if($postulante->estado === 'en_revision')
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded text-yellow-800">
                        <p class="font-bold flex items-center gap-2">⏳ Documentos en Revisión</p>
                        <p class="text-sm mt-1">Tus datos están siendo validados por Administración. Por favor, vuelve a consultar más tarde para realizar tu pago.</p>
                    </div>

                @elseif($postulante->estado === 'inscrito')
                    <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded text-green-800">
                        <p class="font-bold flex items-center gap-2">✅ Inscripción Completada</p>
                        <p class="text-sm mt-1">Tú ya realizaste el pago y eres un estudiante oficial. Por favor, dirígete al panel de inicio de sesión.</p>
                        <a href="{{ route('login') }}" class="inline-block mt-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded transition">Ir a Iniciar Sesión</a>
                    </div>

                @elseif($postulante->estado === 'pendiente_pago')
                    <div class="bg-blue-100 border-l-4 border-blue-500 p-4 rounded text-blue-800 mb-6">
                        <p class="font-bold flex items-center gap-2">💳 Aprobado para Pago</p>
                        <p class="text-sm mt-1">Tus documentos han sido aprobados. Para completar tu inscripción y generar tu cuenta universitaria, realiza el pago de la matrícula.</p>
                    </div>

                    <div class="text-center bg-white p-6 rounded shadow-sm border border-gray-100">
                        <p class="text-gray-500 text-sm font-bold uppercase tracking-widest mb-2">Total a Pagar</p>
                        <p class="text-4xl font-black text-gray-900 mb-6">500.00 Bs.</p>
                        
                        <form action="{{ route('consulta.pagar', $postulante->id) }}" method="POST">
                            @csrf
                            <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="{{ env('STRIPE_KEY') }}"
                                data-amount="50000"
                                data-name="Universidad - CUP"
                                data-description="Matrícula de Admisión"
                                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                data-locale="es"
                                data-currency="bob"
                                data-email="{{ $postulante->correo }}"
                                data-label="Pagar con Tarjeta Segura">
                            </script>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endif
</x-guest-layout>