<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Selección de Grupo CUP') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r shadow-sm">
                    <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    <div class="mb-8 border-b pb-4">
                        <h3 class="text-2xl font-extrabold text-blue-900 mb-2">Elige tu grupo para este semestre</h3>
                        <p class="text-gray-600 text-lg">Tu pago ha sido procesado. Por favor, selecciona un grupo que aún tenga cupos disponibles para completar tu inscripción oficial en la facultad.</p>
                    </div>

                    @if($gruposDisponibles->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg text-center">
                            <div class="text-4xl mb-3">⚠️</div>
                            <h4 class="text-lg font-bold text-yellow-800 mb-1">No hay grupos disponibles</h4>
                            <p class="text-yellow-700">En este momento todos los grupos han alcanzado su límite de 70 estudiantes o aún no se han habilitado. Por favor, contacta con Administración.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($gruposDisponibles as $grupo)
                                <div class="border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-lg hover:border-blue-300 transition-all duration-300 bg-gray-50 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-2xl font-black text-gray-800">{{ $grupo->nombre }}</h4>
                                            
                                            @php $cuposLibres = 70 - $grupo->postulantes_count; @endphp
                                            
                                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $cuposLibres < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $cuposLibres }} cupos libres
                                            </span>
                                        </div>
                                        
                                        <div class="text-sm text-gray-600 mb-6 space-y-2 bg-white p-4 rounded-lg border border-gray-100">
                                            <p class="flex justify-between"><strong>Turno:</strong> <span>{{ $grupo->turno ?? 'No especificado' }}</span></p>
                                            <p class="flex justify-between"><strong>Aula:</strong> <span>{{ $grupo->aula_id ?? 'Por asignar' }}</span></p>
                                            <p class="flex justify-between text-blue-800 font-semibold mt-2 pt-2 border-t">
                                                Inscritos actuales: <span>{{ $grupo->postulantes_count }} / 70</span>
                                            </p>
                                        </div>
                                    </div>

                                    <form action="{{ route('grupo.asignar') }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
                                        <button type="submit" onclick="return confirm('¿Estás seguro de elegir el {{ $grupo->nombre }}? No podrás cambiarlo después.')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow transition transform hover:-translate-y-0.5">
                                            Inscribirme en este Grupo
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>