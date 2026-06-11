<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Postulantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('status'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-sm font-bold">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">📥 Carga Masiva de Postulantes</h3>
                <p class="text-gray-500 text-sm mb-4">Sube el archivo proveído por la facultad (.xlsx o .csv). El sistema creará las cuentas automáticamente usando el correo del alumno y asignará el CI como contraseña inicial.</p>

                <form action="{{ route('postulantes.importar') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4">
                    @csrf
                    <div class="flex-1 w-full">
                        <input type="file" name="archivo" accept=".xlsx, .xls, .csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg p-1 bg-gray-50">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex justify-center items-center gap-2">
                        🚀 Procesar e Inscribir
                    </button>
                </form>
            </div>

            <div class="mb-6 flex justify-between items-center">
                <form method="GET" action="{{ route('postulantes.index') }}" class="flex w-full max-w-lg">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por Nombre o CI..." class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-l-md shadow-sm">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-r-md shadow-sm transition">
                        Buscar
                    </button>
                    @if(!empty($search))
                        <a href="{{ route('postulantes.index') }}" class="ml-3 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md shadow-sm transition flex items-center justify-center">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $postulante->correo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $postulante->primeraOpcion->nombre ?? 'Sin asignar' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $postulante->segundaOpcion->nombre ?? 'Sin asignar' }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($postulante->estado === 'en_revision')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            En Revisión
                                        </span>
                                    @elseif($postulante->estado === 'inscrito')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Inscrito Oficial
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $postulante->estado)) }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    
                                    @if($postulante->estado === 'en_revision')
                                        <form action="{{ route('postulantes.aprobar', $postulante->id) }}" method="POST" class="inline-block mr-3">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-bold" onclick="return confirm('¿Los documentos son correctos? Al aceptar, se le habilitará la pasarela de pago al alumno.')">
                                                ✅ Aprobar para Pago
                                            </button>
                                        </form>
                                    @endif

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
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No se encontraron postulantes registrados. Sube un archivo Excel para comenzar.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $postulantes->appends(['search' => $search])->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>