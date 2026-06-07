<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->rol === 'admin' ? __('Supervisión de Calificaciones') : __('Mis Planillas de Notas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(!$gestionActiva)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm mb-6">
                    <h4 class="text-yellow-800 font-bold">Semestre Inactivo</h4>
                    <p class="text-yellow-700 text-sm">No hay un semestre activo para registrar calificaciones.</p>
                </div>
            @else
                
                <div class="mb-6 flex justify-between items-end">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800">Planillas Habilitadas</h3>
                        <p class="text-gray-500 text-sm">Gestión activa: <span class="font-bold text-blue-600">{{ $gestionActiva->nombre }}</span></p>
                    </div>
                </div>

                @if($asignaciones->isEmpty())
                    <div class="bg-white p-12 text-center rounded-xl shadow-sm border border-gray-200">
                        <div class="text-5xl mb-4 text-gray-300">📁</div>
                        <p class="text-xl font-bold text-gray-500">No tienes grupos asignados</p>
                        <p class="text-gray-400 mt-2 text-sm">Si crees que esto es un error, contacta al administrador.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($asignaciones as $asignacion)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                                <div class="bg-gradient-to-r from-blue-800 to-blue-900 px-6 py-4 flex justify-between items-center">
                                    <h4 class="text-white font-bold text-lg">{{ $asignacion->materia->nombre }}</h4>
                                    <span class="bg-white/20 text-white text-xs px-2 py-1 rounded font-bold">
                                        {{ $asignacion->grupo->turno }}
                                    </span>
                                </div>
                                
                                <div class="p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="bg-blue-100 text-blue-800 px-3 py-1.5 rounded-lg font-black text-xl">
                                            {{ $asignacion->grupo->nombre }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Aula: <span class="font-bold text-gray-800">{{ $asignacion->grupo->aula->nombre ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    
                                    @if(Auth::user()->rol === 'admin')
                                        <div class="mb-4 text-sm bg-gray-50 p-2 rounded border border-gray-100">
                                            <span class="text-gray-400 font-bold text-xs uppercase block mb-1">Docente Titular:</span>
                                            <span class="text-gray-800 font-semibold">{{ $asignacion->docente->nombre }}</span>
                                        </div>
                                    @endif

                                    <a href="{{ route('calificaciones.planilla', $asignacion->id) }}" class="mt-2 block w-full bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 text-center font-bold py-2.5 rounded-lg transition">
                                        📝 Ingresar Notas
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>