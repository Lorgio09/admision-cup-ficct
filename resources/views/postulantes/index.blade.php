<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Postulantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <form method="GET" action="{{ route('postulantes.index') }}" class="flex w-full max-w-lg">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por Nombre o CI..." class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-l-md shadow-sm">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-r-md shadow-sm transition">
                        Buscar
                    </button>
                    @if(!empty($search))
                        <a href="{{ route('postulantes.index') }}" class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition flex items-center justify-center">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Primera Opción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Segunda Opción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($postulantes as $postulante)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $postulante->ci }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $postulante->nombre }}</td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $postulante->primeraOpcion->nombre ?? 'Sin asignar' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $postulante->segundaOpcion->nombre ?? 'Sin asignar' }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($postulante->estado === 'pendiente')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Pagado / En Revisión
                                        </span>
                                    @elseif($postulante->estado === 'inscrito')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Inscrito Oficial
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($postulante->estado) }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    
                                    @if($postulante->estado === 'pendiente')
                                        <form action="{{ route('postulantes.aprobar', $postulante->id) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-bold" onclick="return confirm('¿Confirmas que los documentos son correctos y deseas inscribir oficialmente a este postulante?')">
                                                Inscribir Alumno
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('postulantes.evaluar', $postulante->id) }}" class="text-orange-600 hover:text-orange-900 font-semibold mr-3">
                                        Evaluar
                                    </a>

                                    <a href="{{ route('postulantes.edit', $postulante->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                    
                                    <form action="{{ route('postulantes.destroy', $postulante->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('¿Estás seguro de eliminar a este postulante del sistema?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($postulantes->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No se encontraron postulantes con esos datos.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $postulantes->appends(['search' => $search])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>