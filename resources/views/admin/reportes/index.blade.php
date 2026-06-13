<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes Estadísticos y Académicos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div>
                        <h3 class="text-2xl font-black text-gray-800">Módulo de Reportes Oficiales</h3>
                        <p class="text-gray-500 text-sm">Periodo visualizado actualmente: <span class="font-bold text-blue-600">{{ $gestionActiva->nombre ?? 'Ninguno' }}</span></p>
                    </div>
    
                    <form action="{{ route('reportes.index') }}" method="GET" class="inline-block">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Cambiar de Gestión</label>
                        <select name="gestion_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 shadow-sm text-sm font-bold text-gray-700 bg-gray-50 focus:border-blue-500 focus:ring-blue-500">
                            @foreach($gestiones as $g)
                                <option value="{{ $g->id }}" {{ $gestionActiva && $gestionActiva->id == $g->id ? 'selected' : '' }}>
                                    {{ $g->nombre }} {{ $g->is_active ? '(Actual)' : '(Histórico)' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>                
                <div class="flex gap-2">
                    <a href="{{ route('reportes.pdf', ['gestion_id' => $gestionActiva->id ?? '']) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow text-sm flex items-center gap-2 transition">
                        📄 Exportar Acta PDF
                    </a>
                    <a href="{{ route('reportes.excel', ['gestion_id' => $gestionActiva->id ?? '']) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow text-sm flex items-center gap-2 transition">
                        📊 Exportar Excel
                    </a>
                </div>
            </div>

            @if(!$gestionActiva)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded shadow-sm">
                    <p class="text-yellow-700 font-bold">No hay ninguna gestión activa. El panel de reportes está vacío.</p>
                </div>
            @else

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Total Postulantes</p>
                        <p class="text-3xl font-black text-blue-600">{{ $kpis['total'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Aprobados</p>
                        <p class="text-3xl font-black text-green-600">{{ $kpis['aprobados'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Reprobados</p>
                        <p class="text-3xl font-black text-red-600">{{ $kpis['reprobados'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Promedio Gral.</p>
                        <p class="text-3xl font-black text-purple-600">{{ $kpis['promedio'] }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Grupos Habilitados</p>
                        <p class="text-3xl font-black text-gray-800">{{ $kpis['grupos'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">🏆 Grupos con Mayor Aprobación</h4>
                        <ul class="divide-y divide-gray-100">
                            @forelse($rankingGrupos as $index => $rank)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg font-black text-gray-400">#{{ $index + 1 }}</span>
                                        <span class="font-bold text-gray-700">{{ $rank->grupo->nombre ?? 'Sin Asignar' }}</span>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">{{ $rank->total_aprobados }} aprobados</span>
                                </li>
                            @empty
                                <li class="py-3 text-gray-500 italic text-sm">No hay registros suficientes.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">📚 Estadísticas por Materia</h4>
                        <ul class="divide-y divide-gray-100">
                            @forelse($estadisticasMaterias as $stat)
                                <li class="py-3">
                                    <p class="font-bold text-gray-700 mb-1">{{ $stat->nombre }}</p>
                                    <div class="flex gap-4 text-sm">
                                        <span class="text-green-600 font-semibold">Aprobados: {{ $stat->aprobados }}</span>
                                        <span class="text-red-600 font-semibold">Reprobados: {{ $stat->reprobados }}</span>
                                    </div>
                                    @php $totalMateria = $stat->aprobados + $stat->reprobados; $porcentaje = $totalMateria > 0 ? ($stat->aprobados / $totalMateria) * 100 : 0; @endphp
                                    <div class="w-full bg-red-100 rounded-full h-2 mt-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-gray-500 italic text-sm">No hay evaluaciones registradas.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">👨‍🏫 Docentes por Grupos</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($docentesPorGrupo as $nombreGrupo => $asignaciones)
                            <div class="bg-gray-50 p-4 rounded border border-gray-100">
                                <h5 class="font-black text-blue-900 border-b border-gray-200 pb-1 mb-2">{{ $nombreGrupo }}</h5>
                                <ul class="text-sm text-gray-600">
                                    @foreach($asignaciones as $asig)
                                        <li class="mb-1"><span class="font-bold">{{ $asig->materia->nombre }}:</span> {{ $asig->docente->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-gray-500 italic text-sm">No hay docentes asignados a grupos.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">📋 Lista General de Postulantes</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 text-gray-600 font-bold uppercase">
                                <tr>
                                    <th class="px-4 py-2">CI</th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">Grupo</th>
                                    <th class="px-4 py-2 text-center">Promedio</th>
                                    <th class="px-4 py-2 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($listaGeneral as $postulante)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $postulante->ci }}</td>
                                        <td class="px-4 py-2 font-semibold">{{ $postulante->nombre }}</td>
                                        <td class="px-4 py-2 text-gray-500">{{ $postulante->grupo->nombre ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 text-center font-bold">{{ $postulante->promedio ?? '--' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            @if($postulante->promedio >= 60)
                                                <span class="text-green-600 font-bold">Aprobado</span>
                                            @elseif($postulante->promedio !== null && $postulante->promedio < 60)
                                                <span class="text-red-600 font-bold">Reprobado</span>
                                            @else
                                                <span class="text-gray-400">En proceso</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No hay postulantes registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>