<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Materias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium">Gestión de Materias</h3>
                    <a href="{{ route('materias.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        + Nueva Materia
                    </a>
                </div>

                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase tracking-wider border-b-2 font-semibold">
                                <tr>
                                    <th scope="col" class="px-6 py-4">ID</th>
                                    <th scope="col" class="px-6 py-4">Código</th>
                                    <th scope="col" class="px-6 py-4">Nombre de la Materia</th>
                                    <th scope="col" class="px-6 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materias as $materia)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $materia->id }}</td>
                                        <td class="px-6 py-4 font-bold">{{ $materia->codigo }}</td>
                                        <td class="px-6 py-4">{{ $materia->nombre }}</td>
                                        <td class="px-6 py-4 flex justify-center gap-3">
                                            <a href="{{ route('materias.edit', $materia) }}" class="text-blue-500 hover:text-blue-700 font-medium">
                                                Editar
                                            </a>
                                            <form action="{{ route('materias.destroy', $materia) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta materia?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No hay materias registradas aún.
                                        </td>
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