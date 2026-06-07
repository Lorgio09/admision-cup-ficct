<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Docente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('docentes.update', $docente) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="ci" class="block text-sm font-bold text-gray-700 mb-2">Cédula de Identidad (CI)</label>
                                <input type="text" name="ci" id="ci" value="{{ old('ci', $docente->ci) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ci') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">Nombre Completo</label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $docente->nombre) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-bold text-gray-700 mb-2">Teléfono de Contacto</label>
                                <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $docente->telefono) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="correo" class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico (Usuario)</label>
                                <input type="email" name="correo" id="correo" value="{{ old('correo', $docente->correo) }}" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('correo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-8">
                            <label for="materia_id" class="block text-sm font-bold text-gray-700 mb-2">Materia Asignada</label>
                            <select name="materia_id" id="materia_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled>-- Seleccione una materia --</option>
                                @foreach($materias as $materia)
                                    <option value="{{ $materia->id }}" 
                                        {{ old('materia_id', $docente->materia_id) == $materia->id ? 'selected' : '' }}>
                                        {{ $materia->nombre }} ({{ $materia->codigo }})
                                    </option>
                                @endforeach
                            </select>
                            @error('materia_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-4">
                            <a href="{{ route('docentes.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>