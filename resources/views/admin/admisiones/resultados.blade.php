<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte Oficial de Admisiones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-black text-gray-800">Resultados Académicos</h3>
                    <p class="text-gray-500 text-sm">Gestión activa: <span class="font-bold text-blue-600">{{ $gestionActiva->nombre ?? 'Ninguna' }}</span></p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 font-bold underline text-sm">
                    Volver al Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-800 text-white font-bold uppercase tracking-wider border-b">
                            <tr>
                                <th class="px-6 py-4 text-center w-16">Nro.</th>
                                <th class="px-6 py-4">CI</th>
                                <th class="px-6 py-4">Postulante</th>
                                <th class="px-6 py-4 text-center">Promedio</th>
                                <th class="px-6 py-4">Carrera Asignada</th>
                                <th class="px-6 py-4 text-center">Estado Oficial</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($postulantes as $index => $postulante)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-center text-gray-400 font-mono">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $postulante->ci }}</td>
                                    <td class="px-6 py-4 text-gray-600 font-bold">{{ $postulante->nombre }}</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-black @if($postulante->promedio >= 60) text-green-600 @else text-red-600 @endif">
                                            {{ $postulante->promedio }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($postulante->carreraAdmitida)
                                            <span class="font-bold text-blue-800">{{ $postulante->carreraAdmitida->nombre }}</span>
                                        @else
                                            <span class="text-gray-400 italic">--</span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        @if($postulante->estado === 'ADMITIDO')
                                            <span class="bg-green-100 text-green-800 border border-green-200 font-black px-3 py-1 rounded-full text-xs shadow-sm">ADMITIDO</span>
                                        @elseif($postulante->estado === 'APROBADO_SIN_CUPO')
                                            <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 font-bold px-3 py-1 rounded-full text-xs shadow-sm">SIN CUPO</span>
                                        @elseif($postulante->estado === 'REPROBADO')
                                            <span class="bg-red-100 text-red-800 border border-red-200 font-bold px-3 py-1 rounded-full text-xs shadow-sm">REPROBADO</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 border border-blue-200 font-bold px-3 py-1 rounded-full text-xs shadow-sm">EVALUADO</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-lg font-medium">
                                        <div class="text-4xl mb-2">📊</div>
                                        Aún no hay resultados procesados para este semestre.<br>
                                        <span class="text-sm text-gray-400">Las notas aparecerán aquí cuando los docentes terminen de calificar.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>