<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Exámenes: ') }} {{ $postulante->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <div class="mb-6 bg-blue-50 p-4 rounded-md border-l-4 border-blue-500">
                        <p class="font-bold text-blue-900">Opciones de Carrera del Postulante:</p>
                        <ul class="list-disc ml-5 text-sm text-blue-800 mt-2">
                            <li><strong>Primera Opción:</strong> {{ $postulante->primeraOpcion->nombre }}</li>
                            <li><strong>Segunda Opción:</strong> {{ $postulante->segundaOpcion->nombre }}</li>
                        </ul>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                            <p class="font-bold">Error en los datos:</p>
                            <ul class="list-disc ml-5 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('postulantes.calificar', $postulante->id) }}">
                        @csrf

                        <p class="text-sm text-gray-600 mb-4 font-semibold">Seleccione las 3 materias evaluadas e ingrese la nota (0 a 100):</p>

                        @for($i = 0; $i < 3; $i++)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4 items-end bg-gray-50 p-4 rounded-md border border-gray-200">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Materia Evaluada {{ $i + 1 }}</label>
                                    <select name="materias[]" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">-- Seleccionar Materia --</option>
                                        @foreach($materias as $materia)
                                            <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nota Obtenida</label>
                                    <input type="number" name="notas[]" min="0" max="100" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm font-bold text-lg" placeholder="Ej: 85" required>
                                </div>
                            </div>
                        @endfor

                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('postulantes.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-md transition">
                                Calcular Promedio y Guardar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>