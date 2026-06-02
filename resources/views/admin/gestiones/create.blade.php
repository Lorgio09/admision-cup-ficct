<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Aperturar Nuevo Semestre') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('gestiones.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">Nombre del Periodo Académico</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Ej: II-2026" required
                                class="w-full sm:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-2">Este nombre será visible para los postulantes.</p>
                        </div>

                        <hr class="mb-8">

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Asignación de Cupos por Carrera</h3>
                        <p class="text-gray-600 mb-6">Define la capacidad máxima de admitidos para cada programa en este semestre.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                            @foreach($carreras as $carrera)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <label class="block text-md font-bold text-gray-800 mb-2">{{ $carrera->nombre }}</label>
                                    <div class="flex items-center">
                                        <input type="number" name="cupos[{{ $carrera->id }}]" min="0" value="0" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-3 text-gray-600 font-medium text-sm">estudiantes</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                                Guardar y Habilitar Semestre
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>