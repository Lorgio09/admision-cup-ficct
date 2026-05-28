<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Materia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('materias.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="codigo" class="block font-medium text-sm text-gray-700">Código de la Materia</label>
                            <input id="codigo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" type="text" name="codigo" value="{{ old('codigo') }}" placeholder="Ej: MAT-101" required autofocus />
                            @error('codigo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre de la Materia</label>
                            <input id="nombre" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Cálculo I" required />
                            @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md" href="{{ route('materias.index') }}">
                                Cancelar
                            </a>

                            <button type="submit" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                Guardar Materia
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>