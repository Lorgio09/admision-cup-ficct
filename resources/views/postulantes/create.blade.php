<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Formulario de Inscripción al CUP</h2>
        <p class="text-gray-600 text-sm mt-1">Periodo Académico: Gestión I - 2026</p>
    </div>

    <form method="POST" action="{{ route('postulantes.store') }}">
        @csrf

        <div class="space-y-4">
            <div class="border-b border-gray-200 pb-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">1. Datos Personales</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="ci" class="block font-medium text-sm text-gray-700">Carnet de Identidad (CI)</label>
                    <input id="ci" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="ci" value="{{ old('ci') }}" required autofocus />
                    @error('ci') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="sexo" class="block font-medium text-sm text-gray-700">Sexo</label>
                    <select id="sexo" name="sexo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Selecciona tu sexo...</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="nombre" class="block font-medium text-sm text-gray-700">Nombre Completo</label>
                <input id="nombre" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="nombre" value="{{ old('nombre') }}" required />
                @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="correo" class="block font-medium text-sm text-gray-700">Correo Electrónico</label>
                    <input id="correo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="correo" value="{{ old('correo') }}" required />
                    @error('correo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="telefono" class="block font-medium text-sm text-gray-700">Teléfono / Celular</label>
                    <input id="telefono" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="telefono" value="{{ old('telefono') }}" required />
                    @error('telefono') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="direccion" class="block font-medium text-sm text-gray-700">Dirección de Domicilio</label>
                <input id="direccion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="direccion" value="{{ old('direccion') }}" required />
                @error('direccion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="fecha_nacimiento" class="block font-medium text-sm text-gray-700">Fecha de Nacimiento</label>
                    <input id="fecha_nacimiento" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required />
                    @error('fecha_nacimiento') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label for="colegio_procedencia" class="block font-medium text-sm text-gray-700">Colegio de Procedencia</label>
                    <input id="colegio_procedencia" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" type="text" name="colegio_procedencia" value="{{ old('colegio_procedencia') }}" placeholder="Ej: Colegio Nacional Florida" required />
                    @error('colegio_procedencia') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="ciudad_residencia" class="block font-medium text-sm text-gray-700">Ciudad de donde vive actualmente</label>
                    <input id="ciudad_residencia" class="block mt-1 w-full border-gray-300 focus:border-blue-500 rounded-md shadow-sm" type="text" name="ciudad_residencia" value="{{ old('ciudad_residencia') }}" placeholder="Ej: Santa Cruz de la Sierra" required />
                    @error('ciudad_residencia') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="border-b border-gray-200 pb-2 pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">2. Selección de Carreras</h3>
            </div>

            <div>
                <label for="carrera_primera_opcion_id" class="block font-medium text-sm text-gray-700">Primera Opción de Carrera</label>
                <select id="carrera_primera_opcion_id" name="carrera_primera_opcion_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Selecciona tu carrera principal...</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id }}" {{ old('carrera_primera_opcion_id') == $carrera->id ? 'selected' : '' }}>
                            {{ $carrera->codigo }} - {{ $carrera->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('carrera_primera_opcion_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="carrera_segunda_opcion_id" class="block font-medium text-sm text-gray-700">Segunda Opción de Carrera</label>
                <select id="carrera_segunda_opcion_id" name="carrera_segunda_opcion_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="" disabled selected>Selecciona tu carrera secundaria...</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id }}" {{ old('carrera_segunda_opcion_id') == $carrera->id ? 'selected' : '' }}>
                            {{ $carrera->codigo }} - {{ $carrera->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('carrera_segunda_opcion_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="border-b border-gray-200 pb-2 pt-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">3. Requisitos de Admisión</h3>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label for="certificado_bachiller" class="inline-flex items-start">
                    <input id="certificado_bachiller" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 mt-1" name="certificado_bachiller" required>
                    <span class="ml-3 text-sm text-gray-600 leading-relaxed">
                        Declaro bajo juramento que cuento con mi **Certificado de Bachiller** original físico para su posterior verificación en las oficinas académicas.
                    </span>
                </label>
                @error('certificado_bachiller') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end mt-6 border-t border-gray-100 pt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md transition" href="{{ url('/') }}">
                Cancelar
            </a>
            <button type="submit" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-md shadow negotiation transition duration-150 ease-in-out">
                Continuar al Pago
            </button>
        </div>
    </form>
</x-guest-layout>