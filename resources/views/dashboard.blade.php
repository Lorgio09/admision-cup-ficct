<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
                    {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->rol === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                        
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
                    </div>
                </div>

            @elseif(Auth::user()->rol === 'docente')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Has iniciado sesión como <strong>Docente</strong>.</p>
                        <p class="mt-2 text-gray-500">Próximamente aquí podrás ver las materias que te han sido asignadas y registrar las calificaciones de tus postulantes.</p>
                    </div>
                </div>

            @elseif(Auth::user()->rol === 'postulante' && isset($postulante))
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 p-4 rounded-full text-3xl">🎓</div>
                        <div>
                            <h3 class="text-xl font-black text-gray-900">¡Bienvenido, {{ $postulante->nombre }}!</h3>
                            <p class="text-sm text-gray-500 mt-1">Tu inscripción al Curso Preuniversitario (CUP) está confirmada.</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-lg border border-gray-200 text-center md:text-right w-full md:w-auto">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tu Asignación Física</p>
                        <p class="text-xl font-black text-blue-900">{{ $postulante->grupo->nombre ?? 'Sin grupo' }}</p>
                        <div class="flex gap-2 justify-center md:justify-end mt-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">Turno: {{ $postulante->grupo->turno ?? 'N/A' }}</span>
                            <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded">Aula: {{ $postulante->grupo->aula->nombre ?? 'Por definir' }}</span>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Mis Materias y Docentes</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if($postulante->grupo && $postulante->grupo->asignaciones->count() > 0)
                        @foreach($postulante->grupo->asignaciones as $asignacion)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                                <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-3">
                                    <div class="bg-blue-50 p-2 rounded-lg text-xl">📚</div>
                                    <h4 class="font-bold text-gray-800">{{ $asignacion->materia->nombre }}</h4>
                                </div>
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase">Docente Titular</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $asignacion->docente->nombre }}</p>
                                </div>
                                <div class="bg-gray-50 rounded p-3 text-center border border-gray-100">
                                    <p class="text-xs text-gray-500 font-medium">Calificación</p>
                                    <p class="text-lg font-black text-gray-300">-- / 100</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-4 bg-white p-8 text-center rounded-xl border border-gray-200 text-gray-500">
                            Aún no se han asignado docentes a tu grupo.
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>