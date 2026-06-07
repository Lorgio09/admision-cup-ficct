<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista de Docentes</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex justify-between items-center border-b border-gray-200">
                    <h3 class="text-lg font-medium">Gestión de Docentes</h3>
                    <a href="{{ route('docentes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        + Nuevo Docente
                    </a>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase tracking-wider border-b-2 font-semibold">
                                <tr>
                                    <th class="px-6 py-4">CI</th>
                                    <th class="px-6 py-4">Nombre Completo</th>
                                    <th class="px-6 py-4">Teléfono</th>
                                    <th class="px-6 py-4">Materia</th>
                                    <th class="px-6 py-4">Correo Electrónico (Usuario)</th>
                                    <th class="px-6 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($docentes as $docente)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold">{{ $docente->ci }}</td>
                                        <td class="px-6 py-4">{{ $docente->nombre }}</td>
                                        <td class="px-6 py-4">{{ $docente->telefono }}</td>
                                        <td class="px-6 py-4">{{ $docente->materia->nombre ?? 'Sin asignar' }}</td>
                                        <td class="px-6 py-4 text-blue-600">{{ $docente->user->email ?? 'Sin cuenta' }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('docentes.edit', $docente) }}" class="text-blue-500 hover:text-blue-700 font-medium">Editar</a>
                                            <form action="{{ route('docentes.destroy', $docente) }}" method="POST" onsubmit="return confirm('¿Eliminar este docente y su acceso al sistema?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay docentes registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>