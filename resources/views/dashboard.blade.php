<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    
                    @if(Auth::user()->rol === 'admin')
                        <p class="text-gray-600 mb-6">Has iniciado sesión como <strong>Administrador</strong>. Desde aquí puedes gestionar los catálogos del sistema, asignar cupos y registrar nuevo personal.</p>
                        
                        @if(isset($gestionActiva))
                            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center">
                                <div class="text-3xl mr-4">📖</div>
                                <div>
                                    <h4 class="text-lg font-bold text-green-800">Semestre Actual: {{ $gestionActiva->nombre }}</h4>
                                    <p class="text-green-700 text-sm">Este periodo está habilitado y recibiendo inscripciones. Si aperturas uno nuevo, este se cerrará automáticamente.</p>
                                </div>
                            </div>
                        @else
                            <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm flex items-center">
                                <div class="text-3xl mr-4">⚠️</div>
                                <div>
                                    <h4 class="text-lg font-bold text-yellow-800">Sistema en Pausa</h4>
                                    <p class="text-yellow-700 text-sm">No hay ningún semestre activo en este momento. Los estudiantes no podrán inscribirse hasta que apertures uno.</p>
                                </div>
                            </div>
                        @endif
                        <div class="p-6 bg-gray-50 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Gestión Académica</h3>
                                <p class="text-gray-600 text-sm">Administra los periodos de inscripción y los cupos por cada carrera.</p>
                            </div>
                            <div>
                                <a href="{{ route('gestiones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:-translate-y-0.5 inline-flex items-center gap-2 text-center w-full sm:w-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Aperturar Nuevo Semestre
                                </a>
                            </div>
                        </div>
                    
                    @elseif(Auth::user()->rol === 'docente')
                        <p class="text-gray-600">Has iniciado sesión como <strong>Docente</strong>.</p>
                        <p class="mt-2">Próximamente aquí podrás ver las materias que te han sido asignadas y registrar las calificaciones de tus postulantes.</p>
                    
                    @else
                        <p class="text-gray-600">Has iniciado sesión como <strong>Postulante</strong>.</p>
                        <p class="mt-2">Aquí podrás ver el estado de tu inscripción y tu boleta asignada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>