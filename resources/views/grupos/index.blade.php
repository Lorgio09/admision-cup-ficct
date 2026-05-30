<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Módulo de Asignación de Grupos - CUP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Alumnos Inscritos</p>
                                <p class="text-3xl font-extrabold text-blue-900 mt-1">{{ $totalInscritos }}</p>
                            </div>
                            <div class="text-3xl p-3 bg-blue-50 rounded-full">👨‍🎓</div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Habilitados para el curso de nivelación</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Grupos Habilitados</p>
                                <p class="text-3xl font-extrabold text-green-900 mt-1">{{ $grupos->count() }}</p>
                            </div>
                            <div class="text-3xl p-3 bg-green-50 rounded-full">🏫</div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Calculado bajo la regla: máx. 70 por grupo</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Alumnos por Asignar</p>
                                <p class="text-3xl font-extrabold text-amber-900 mt-1">{{ $pendientesDeAsignar }}</p>
                            </div>
                            <div class="text-3xl p-3 bg-amber-50 rounded-full">⏳</div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Inscritos que aún no tienen aula ni turno</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Crear Nuevo Grupo Físico</h3>
                <form action="{{ route('grupos.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre del Grupo</label>
                        <input type="text" name="nombre" placeholder="Ej: SA, SB, Grupo 1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Turno</label>
                        <select name="turno" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="Mañana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Noche">Noche</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Aula Asignada</label>
                        <select name="aula_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Sin asignar --</option>
                            @foreach($aulas as $aula)
                                <option value="{{ $aula->id }}">{{ $aula->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded shadow-md transition">
                            + Guardar Grupo
                        </button>
                    </div>
                </form>
            </div>

            @if($pendientesDeAsignar > 0)
                <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-lg shadow-md p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-bold">Distribución de Alumnos Pendiente</h3>
                        <p class="text-blue-200 text-sm mt-1">
                            Hay {{ $pendientesDeAsignar }} postulantes listos. El sistema los insertará automáticamente en los grupos que hayas creado que aún tengan sillas disponibles (Máx. 70).
                        </p>
                    </div>
                    <form action="{{ route('grupos.generar') }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-gray-900 font-extrabold py-3 px-6 rounded-lg shadow transition transform hover:-translate-y-0.5 text-center">
                            🚀 Llenar Grupos Existentes
                        </button>
                    </form>
                </div>
            @endif

            <h3 class="text-xl font-bold text-gray-800 mb-4 tracking-tight">Distribución Física de Aulas</h3>

            @if($grupos->isEmpty())
                <div class="bg-white p-12 rounded-lg shadow-sm text-center text-gray-500 border border-gray-200">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-lg font-medium">Aún no se han generado los grupos del CUP.</p>
                    <p class="text-sm text-gray-400 mt-1">Cuando los postulantes completen sus formularios e inscripciones, podrás procesarlos aquí.</p>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($grupos as $grupo)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl font-bold text-gray-900">{{ $grupo->nombre }}</span>
                                    <span class="px-3 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Turno: {{ $grupo->turno }}
                                    </span>
                                    <span class="px-3 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Aula: {{ $grupo->aula->nombre ?? 'Sin Aula Asignada' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-sm font-semibold text-gray-600">
                                    <span>Total en este grupo: <span class="text-blue-600 font-bold text-base">{{ $grupo->postulantes->count() }}</span> / 70 estudiantes</span>
                                    
                                    <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md transition font-bold" onclick="return confirm('¿Estás seguro de eliminar {{ $grupo->nombre }}? Los alumnos que estaban aquí volverán a la lista de pendientes.')">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="p-4 overflow-x-auto max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nro.</th>
                                            <th class="px-6 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">CI</th>
                                            <th class="px-6 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                            <th class="px-6 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Primera Opción Carrera</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($grupo->postulantes as $index => $alumno)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-400 font-mono">{{ $index + 1 }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $alumno->ci }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">{{ $alumno->nombre }}</td>
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $alumno->primeraOpcion->nombre ?? 'Sin carrera' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>