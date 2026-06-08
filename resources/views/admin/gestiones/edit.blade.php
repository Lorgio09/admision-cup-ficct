<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Gestión y Cupos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-2xl font-black text-gray-800">Ajustes del Semestre</h3>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 font-bold underline text-sm">
                    Volver al Dashboard
                </a>
            </div>

            <form action="{{ route('gestiones.update', $gestion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8 p-6">
                    <h4 class="text-lg font-bold text-blue-900 mb-4 border-b pb-2">Información de la Gestión</h4>
                    
                    <div class="w-full md:w-1/2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Semestre / Gestión</label>
                        <input type="text" name="nombre" value="{{ $gestion->nombre }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 font-semibold" required>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h4 class="text-lg font-bold text-gray-900">Ajuste de Cupos por Carrera</h4>
                        <p class="text-sm text-gray-500">Modifica la cantidad de alumnos que pueden ser admitidos en este periodo específico.</p>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($carreras as $carrera)
                                @php
                                    // Buscamos el cupo en la tabla pivote para rellenar el input
                                    $carreraAsignada = $gestion->carreras->firstWhere('id', $carrera->id);
                                    $cupoActual = $carreraAsignada ? $carreraAsignada->pivot->cupo_maximo : 0;
                                @endphp
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg bg-white hover:shadow-sm transition">
                                    <div class="flex items-center gap-3">
                                        <div class="text-2xl">🎓</div>
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $carrera->nombre }}</p>
                                        </div>
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-bold text-gray-500 text-center mb-1">Cupos</label>
                                        <input type="number" name="cupos[{{ $carrera->id }}]" value="{{ $cupoActual }}" min="0" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-center font-black text-lg text-blue-800 bg-blue-50" required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2 text-lg">
                        💾 Guardar Todos los Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>