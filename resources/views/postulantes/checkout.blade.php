<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Pasarela de Pago Segura</h2>
        <p class="text-gray-600 text-sm mt-1">Completando la inscripción de {{ $datos['nombre'] }}</p>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-md">
        <div class="flex justify-between items-center">
            <span class="font-semibold text-blue-800">Total a Pagar (CUP):</span>
            <span class="text-2xl font-bold text-blue-900">700 Bs.</span>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
            <p class="font-bold">Hubo un problema:</p>
            <p>{{ $errors->first() }}</p>
        </div>
    @endif <form action="{{ route('postulantes.procesarPago') }}" method="POST" id="payment-form">
        @csrf

        <div class="mb-4">
            <label for="card-element" class="block font-medium text-sm text-gray-700 mb-2">
                Tarjeta de Crédito o Débito
            </label>
            <div id="card-element" class="p-3 border border-gray-300 rounded-md bg-white shadow-sm"></div>
            <div id="card-errors" role="alert" class="text-red-500 text-xs mt-2 font-semibold"></div>
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                Cancelar y salir
            </a>
            <button id="submit-button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-md shadow transition w-1/2">
                Pagar 700 Bs.
            </button>
        </div>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Inicializamos Stripe con la llave pública del .env
        var stripe = Stripe('{{ env("STRIPE_KEY") }}');
        var elements = stripe.elements();

        // Creamos el elemento de la tarjeta con un diseño limpio
        var card = elements.create('card', {
            style: {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': { color: '#aab7c4' }
                },
                invalid: { color: '#fa755a', iconColor: '#fa755a' }
            }
        });

        // Lo montamos en el div #card-element
        card.mount('#card-element');

        // Manejamos los errores de validación en tiempo real
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Interceptamos el envío del formulario para crear el token seguro
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Deshabilitamos el botón para evitar doble cobro
            document.getElementById('submit-button').disabled = true;
            document.getElementById('submit-button').innerText = 'Procesando...';

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    document.getElementById('submit-button').disabled = false;
                    document.getElementById('submit-button').innerText = 'Pagar 700 Bs.';
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            
            form.submit();
        }
    </script>
</x-guest-layout>