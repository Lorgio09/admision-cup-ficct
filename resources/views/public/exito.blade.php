<x-guest-layout>
    <div class="text-center py-8">
        <div class="text-6xl mb-4">🎓</div>
        <h2 class="font-black text-3xl text-gray-800 mb-2">¡Bienvenido a la Universidad!</h2>
        <p class="text-gray-600 mb-8">Tu pago ha sido procesado exitosamente y tu matrícula está confirmada.</p>

        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 text-left shadow-sm max-w-md mx-auto relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
            
            <h3 class="text-indigo-900 font-bold text-lg mb-4 border-b border-indigo-200 pb-2">Tus Credenciales de Acceso</h3>
            
            <p class="text-sm text-indigo-700 mb-4">El sistema ha generado automáticamente tu cuenta para que puedas revisar tus notas y el grupo asignado.</p>

            <div class="mb-3">
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Usuario / Correo Electrónico</p>
                <p class="text-lg font-bold text-gray-900 bg-white p-2 rounded border border-indigo-100 mt-1">{{ $usuario }}</p>
            </div>

            <div class="mb-6">
                <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Contraseña Temporal</p>
                <p class="text-lg font-bold text-gray-900 bg-white p-2 rounded border border-indigo-100 mt-1">{{ $contrasena }}</p>
                <p class="text-xs text-indigo-500 mt-1">* Tu contraseña es tu número de Carnet de Identidad.</p>
            </div>

            <a href="{{ route('login') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                Ir al Portal de Estudiantes
            </a>
        </div>
    </div>
</x-guest-layout>