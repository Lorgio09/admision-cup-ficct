<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Postulante: ') }} {{ $postulante->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <form method="POST" action="{{ route('postulantes.update', $postulante->id) }}">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-bold text-blue-800 border-b pb-2 mb-4">Datos Personales</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Carnet de Identidad (CI)</label>
                                <input type="text" name="ci" value="{{ old('ci', $postulante->ci) }}" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                @error('ci') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Nombre Completo</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $postulante->nombre) }}" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo', $postulante->correo) }}" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Sexo</label>
                                <select name="sexo" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                    <option value="M" {{ old('sexo', $postulante->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $postulante->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $postulante->telefono) }}" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $postulante->direccion) }}" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                        </div>

                        <h3 class="text-lg font-bold text-blue-800 border-b pb-2 mb-4">Datos de Origen</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Ciudad de Nacimiento</label>
                                <input type="text" name="ciudad_nacimiento" value="{{ old('ciudad_nacimiento', $postulante->ciudad_nacimiento) }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $postulante->fecha_nacimiento) }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Colegio de Procedencia</label>
                                <input type="text" name="colegio_procedencia" value="{{ old('colegio_procedencia', $postulante->colegio_procedencia) }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Ciudad donde Vive</label>
                                <input type="text" name="ciudad_residencia" value="{{ old('ciudad_residencia', $postulante->ciudad_residencia) }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-blue-800 border-b pb-2 mb-4 mt-6">Datos Académicos y Estado</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Primera Opción</label>
                                <select name="carrera_primera_opcion_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" {{ old('carrera_primera_opcion_id', $postulante->carrera_primera_opcion_id) == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Segunda Opción</label>
                                <select name="carrera_segunda_opcion_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" required>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->id }}" {{ old('carrera_segunda_opcion_id', $postulante->carrera_segunda_opcion_id) == $carrera->id ? 'selected' : '' }}>
                                            {{ $carrera->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('carrera_segunda_opcion_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm font-bold text-gray-700">Estado del Sistema</label>
                                <select name="estado" class="block mt-1 w-full border-gray-300 bg-yellow-50 focus:border-blue-500 rounded-md shadow-sm font-semibold" required>
                                    <option value="pendiente" {{ old('estado', $postulante->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente / En Revisión</option>
                                    <option value="inscrito" {{ old('estado', $postulante->estado) == 'inscrito' ? 'selected' : '' }}>Inscrito Oficial</option>
                                    <option value="rechazado" {{ old('estado', $postulante->estado) == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-4">
                            <a href="{{ route('postulantes.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded shadow-md transition">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>