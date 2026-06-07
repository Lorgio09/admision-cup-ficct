<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registro de Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-gray-800">{{ $asignacion->materia->nombre }}</h3>
                    <p class="text-gray-500 font-bold">
                        {{ $asignacion->grupo->nombre }} (Turno {{ $asignacion->grupo->turno }})
                    </p>
                </div>
                <a href="{{ route('calificaciones.index') }}" class="text-gray-500 hover:text-gray-800 font-bold underline text-sm">
                    Volver atrás
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="bg-blue-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <span class="text-blue-800 font-bold text-sm uppercase tracking-wider">
                        👨‍🏫 Titular: {{ $asignacion->docente->nombre }}
                    </span>
                </div>

                <form action="{{ route('calificaciones.guardar', $asignacion->id) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-100 font-bold text-gray-600 uppercase tracking-wider border-b">
                                <tr>
                                    <th class="px-4 py-3 w-16 text-center">Nro.</th>
                                    <th class="px-4 py-3">Nombre del Postulante</th>
                                    <th class="px-4 py-3 text-center bg-blue-100 text-blue-900 border-l border-blue-200">Examen 1</th>
                                    <th class="px-4 py-3 text-center bg-blue-100 text-blue-900 border-l border-blue-200">Examen 2</th>
                                    <th class="px-4 py-3 text-center bg-blue-100 text-blue-900 border-l border-blue-200">Examen 3</th>
                                    <th class="px-4 py-3 text-center bg-green-100 text-green-900 border-l border-green-200">Prom. Materia</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($postulantes as $index => $postulante)
                                    @php
                                        $ev = $evaluaciones[$postulante->id] ?? null;
                                        $n1 = $ev->nota1 ?? '';
                                        $n2 = $ev->nota2 ?? '';
                                        $n3 = $ev->nota3 ?? '';
                                        $promedio = ($n1 !== '' && $n2 !== '' && $n3 !== '') ? round(($n1 + $n2 + $n3) / 3, 2) : '--';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-center text-gray-400 font-mono">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $postulante->nombre }}</td>
                                        
                                        <td class="px-2 py-2 text-center"><input type="number" name="nota1[{{ $postulante->id }}]" value="{{ $n1 }}" min="0" max="100" class="w-16 text-center rounded border-gray-300"></td>
                                        <td class="px-2 py-2 text-center"><input type="number" name="nota2[{{ $postulante->id }}]" value="{{ $n2 }}" min="0" max="100" class="w-16 text-center rounded border-gray-300"></td>
                                        <td class="px-2 py-2 text-center"><input type="number" name="nota3[{{ $postulante->id }}]" value="{{ $n3 }}" min="0" max="100" class="w-16 text-center rounded border-gray-300"></td>
                                        
                                        <td class="px-4 py-3 text-center font-black text-lg text-green-700">{{ $promedio }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow transition">
                            💾 Guardar Planilla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>