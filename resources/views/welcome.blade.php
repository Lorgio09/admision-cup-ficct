<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admisión CUP | Inicio</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-900 font-sans selection:bg-blue-600 selection:text-white">
        
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/escudo_facultad.png') }}" alt="Escudo de la Facultad" class="h-16 w-auto">
                    <span class="font-extrabold text-2xl text-blue-900 tracking-tight">Admisión <span class="text-blue-600">CUP</span><span class="text-blue-600"> FICCT</span></span>
                </div>
                
                <div>
                    @if (Route::has('login'))
                        <nav class="flex gap-4 items-center">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-blue-600 transition">
                                    Ir al Panel de Control
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                                    Iniciar Sesión
                                </a>
                            
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex flex-col items-center justify-center mt-24 px-4 sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="max-w-4xl w-full mx-auto mb-10">
                    <div class="bg-green-50 border-l-8 border-green-500 rounded-r-xl shadow-md p-6 flex items-start transform transition-all duration-500 ease-in-out">
                        <div class="text-4xl mr-4">🎉</div>
                        <div>
                            <h3 class="text-xl font-bold text-green-900 mb-1">¡Inscripción Recibida!</h3>
                            <p class="text-green-800 text-md leading-relaxed">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center max-w-4xl">
                <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight sm:text-6xl mb-6">
                    Sistema de Admisión Universitaria
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Plataforma oficial para la gestión integral de postulantes, control de cupos por carrera y registro de evaluaciones.
                </p>

                <div class="mt-4 mb-12 flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('postulantes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 text-center w-full sm:w-auto text-lg">
                        📝 Inscribirse al CUP
                    </a>

                    <a href="{{ route('consulta.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 text-center w-full sm:w-auto text-lg">
                        🔍 Consultar Estado y Pagar
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 max-w-7xl w-full">
                
                <a href="{{ route('postulantes.create') }}" class="block bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-lg hover:-translate-y-1 transform transition duration-300 cursor-pointer">
                    <div class="text-5xl mb-4">📝</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Registro de Postulantes</h3>
                    <p class="text-gray-500 mb-4">Inscripción ágil y control automatizado de primera y segunda opción de carrera.</p>
                </a>
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Gestión de Cupos</h3>
                    <p class="text-gray-500">Administración detallada de carreras, materias, facultades y disponibilidad de aulas.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                    <div class="text-5xl mb-4">👨‍🏫</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Módulo Académico</h3>
                    <p class="text-gray-500">Asignación de grupos, gestión de docentes y registro de calificaciones de exámenes.</p>
                </div>
            </div>
        </main>
        
        <footer class="mt-24 pb-8 text-center text-gray-400 text-sm">
            &copy; 2026 Proyecto Admisión.
        </footer>
    </body>
</html>