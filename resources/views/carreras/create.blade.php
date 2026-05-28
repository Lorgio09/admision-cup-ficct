<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Carrera') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('carreras.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="codigo" class="block font-medium text-sm text-gray-700">Código de la Carrera (Ej: SIS, INF)</label>
                        <input type="text" name="codigo" id="codigo" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('codigo') }}" required>
                        @error('codigo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre de la Carrera</label>
                        <input type="text" name="nombre" id="nombre" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('nombre') }}" required>
                        @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="cupo" class="block font-medium text-sm text-gray-700">Cupo Disponible (Cantidad de estudiantes)</label>
                        <input type="number" name="cupo" id="cupo" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('cupo') }}" min="1" required>
                        @error('cupo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end mt-6 border-t border-gray-200 pt-4">
                        <a href="{{ route('carreras.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 font-medium mt-2">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                            Guardar Carrera
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>