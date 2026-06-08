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
                        
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-gray-800">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                            <p class="text-gray-500 mt-1">Has iniciado sesión como <strong>Administrador</strong>. Desde aquí puedes gestionar los catálogos del sistema, asignar cupos y registrar nuevo personal.</p>
                        </div>
                        
                        @if(isset($gestionActiva))
                            <div class="mb-6 bg-gradient-to-r from-green-50 to-white border border-green-200 rounded-xl shadow-sm p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="text-4xl bg-green-100 p-3 rounded-full shadow-inner">📖</div>
                                    <div>
                                        <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Semestre Activo</p>
                                        <h4 class="text-xl font-black text-green-900">{{ $gestionActiva->nombre }}</h4>
                                        <p class="text-green-700 text-sm mt-1">Este periodo está habilitado y recibiendo inscripciones.</p>
                                    </div>
                                </div>
                                <div class="w-full md:w-auto">
                                    <a href="{{ route('gestiones.edit', $gestionActiva->id) }}" class="flex justify-center items-center gap-2 bg-white border-2 border-green-300 text-green-700 hover:bg-green-50 font-bold py-2.5 px-6 rounded-lg shadow-sm text-sm transition whitespace-nowrap">
                                        ✏️ Editar Gestión y Cupos
                                    </a>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-6">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Acciones de Cierre de Semestre</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <form action="{{ route('gestiones.procesar', $gestionActiva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto calculará los ingresos y descontará los cupos de manera definitiva basándose en las notas más altas.');">
                                        @csrf
                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-4 rounded-lg shadow transition flex justify-center items-center gap-2 group">
                                            <span class="text-xl group-hover:rotate-90 transition transform">⚙️</span> 
                                            Calcular y Asignar Ingresos
                                        </button>
                                    </form>

                                    <a href="{{ route('admisiones.resultados') }}" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg shadow transition flex justify-center items-center gap-2">
                                        <span class="text-xl">📊</span> 
                                        Ver Lista Oficial de Admitidos
                                    </a>
                                </div>
                            </div>
                            
                        @else
                            <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm p-6 flex items-center gap-4">
                                <div class="text-4xl bg-yellow-100 p-3 rounded-full shadow-inner">⚠️</div>
                                <div>
                                    <h4 class="text-lg font-bold text-yellow-800">Sistema en Pausa</h4>
                                    <p class="text-yellow-700 text-sm">No hay ningún semestre activo en este momento. Los estudiantes no podrán inscribirse hasta que apertures uno.</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Gestión Académica Futura</h3>
                                <p class="text-gray-500 text-sm mt-1">Crea un nuevo periodo de inscripción. Al hacerlo, el semestre actual se cerrará automáticamente.</p>
                            </div>
                            <div class="w-full sm:w-auto">
                                <a href="{{ route('gestiones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Has iniciado sesión como <strong>Docente</strong>.</p>
                        <p class="mt-2 text-gray-500">Próximamente aquí podrás ver las materias que te han sido asignadas y registrar las calificaciones de tus postulantes.</p>
                    </div>
                </div>

            @elseif(Auth::user()->rol === 'postulante' && isset($postulante))
                
                @if($postulante->estado === 'ADMITIDO')
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-center text-white mb-8 transform hover:scale-105 transition">
                        <div class="text-6xl mb-4">🎉</div>
                        <h2 class="text-3xl font-black mb-2">¡Felicidades, {{ $postulante->nombre }}!</h2>
                        <p class="text-xl">Has sido <strong>ADMITIDO</strong> oficialmente en la carrera de:</p>
                        <div class="mt-4 inline-block bg-white text-green-700 font-black text-2xl px-6 py-3 rounded-lg shadow-md">
                            {{ $postulante->carreraAdmitida->nombre ?? 'No especificada' }}
                        </div>
                        <p class="mt-6 text-green-100 font-bold">Promedio Final: {{ $postulante->promedio }} / 100</p>
                    </div>

                @elseif($postulante->estado === 'APROBADO_SIN_CUPO')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm p-8 text-center mb-8">
                        <div class="text-5xl mb-4">⚠️</div>
                        <h2 class="text-2xl font-black text-yellow-800 mb-2">Aprobaste, pero no hay cupos</h2>
                        <p class="text-yellow-700">Tu promedio final es de <strong>{{ $postulante->promedio }}</strong>. Aprobaste los exámenes, pero lamentablemente los cupos de tus opciones de carrera se llenaron con promedios más altos.</p>
                    </div>

                @elseif($postulante->estado === 'REPROBADO')
                    <div class="bg-red-50 border border-red-200 rounded-xl shadow-sm p-8 text-center mb-8">
                        <div class="text-5xl mb-4">😔</div>
                        <h2 class="text-2xl font-black text-red-800 mb-2">No alcanzaste la nota mínima</h2>
                        <p class="text-red-700">Tu promedio final fue de <strong>{{ $postulante->promedio }}</strong>. El puntaje mínimo de aprobación es 60. Te deseamos éxito en la próxima gestión.</p>
                    </div>
                
                @elseif($postulante->estado === 'APROBADO')
                    <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-sm p-8 text-center mb-8">
                        <div class="text-5xl mb-4">⏳</div>
                        <h2 class="text-2xl font-black text-blue-800 mb-2">¡Exámenes finalizados!</h2>
                        <p class="text-blue-700">Tu promedio final es <strong>{{ $postulante->promedio }}</strong>. Estamos procesando la asignación de cupos a nivel general. Vuelve a revisar este panel pronto para ver si fuiste admitido.</p>
                    </div>

                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-100 p-4 rounded-full text-3xl shadow-inner">🎓</div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">¡Bienvenido, {{ $postulante->nombre }}!</h3>
                                <p class="text-sm text-gray-500 mt-1">Tu inscripción al Curso Preuniversitario (CUP) está confirmada.</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 rounded-lg border border-gray-200 text-center md:text-right w-full md:w-auto shadow-sm">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tu Asignación Física</p>
                            <p class="text-xl font-black text-blue-900">{{ $postulante->grupo->nombre ?? 'Sin grupo' }}</p>
                            <div class="flex gap-2 justify-center md:justify-end mt-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Turno: {{ $postulante->grupo->turno ?? 'N/A' }}</span>
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Aula: {{ $postulante->grupo->aula->nombre ?? 'Por definir' }}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-4 px-2">Mis Materias y Docentes</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @if($postulante->grupo && $postulante->grupo->asignaciones->count() > 0)
                            @foreach($postulante->grupo->asignaciones as $asignacion)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                                    <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-3">
                                        <div class="bg-blue-50 p-2 rounded-lg text-xl shadow-inner">📚</div>
                                        <h4 class="font-bold text-gray-800">{{ $asignacion->materia->nombre }}</h4>
                                    </div>
                                    <div class="mb-4">
                                        <p class="text-xs font-bold text-gray-400 uppercase">Docente Titular</p>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $asignacion->docente->nombre }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3 text-center border border-gray-100 shadow-inner">
                                        <p class="text-xs text-gray-500 font-medium">Calificación</p>
                                        <p class="text-lg font-black text-gray-400">-- / 100</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-4 bg-white p-8 text-center rounded-xl border border-gray-200 text-gray-500 shadow-sm">
                                <div class="text-4xl mb-2">⏳</div>
                                Aún no se han asignado docentes a tu grupo.
                            </div>
                        @endif
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>