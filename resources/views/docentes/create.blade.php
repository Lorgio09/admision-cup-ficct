<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nuevo Docente</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('docentes.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ci" class="block font-medium text-sm text-gray-700">Carnet de Identidad (CI)</label>
                            <input type="text" name="ci" id="ci" class="border-gray-300 focus:border-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('ci') }}" required>
                            @error('ci') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono / Celular</label>
                            <input type="text" name="telefono" id="telefono" class="border-gray-300 focus:border-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('telefono') }}" required>
                            @error('telefono') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="border-gray-300 focus:border-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('nombre') }}" required>
                        @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-4">
                        <label for="correo" class="block font-medium text-sm text-gray-700">Correo Electrónico (Para iniciar sesión)</label>
                        <input type="email" name="correo" id="correo" class="border-gray-300 focus:border-indigo-500 rounded-md shadow-sm w-full mt-1" value="{{ old('correo') }}" required>
                        <p class="text-xs text-gray-500 mt-1">Se creará una cuenta automáticamente con la contraseña: 12345678</p>
                        @error('correo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end mt-6 border-t pt-4">
                        <a href="{{ route('docentes.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 mt-2">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Guardar Docente</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>