<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    
                    @if(Auth::user()->rol === 'admin')
                        <p class="text-gray-600">Has iniciado sesión como <strong>Administrador</strong>.</p>
                        <p class="mt-2">Desde el menú superior puedes gestionar los catálogos del sistema, asignar cupos y registrar nuevo personal.</p>
                    
                    @elseif(Auth::user()->rol === 'docente')
                        <p class="text-gray-600">Has iniciado sesión como <strong>Docente</strong>.</p>
                        <p class="mt-2">Próximamente aquí podrás ver las materias que te han sido asignadas y registrar las calificaciones de tus postulantes.</p>
                    
                    @else
                        <p class="text-gray-600">Has iniciado sesión como <strong>Postulante</strong>.</p>
                        <p class="mt-2">Aquí podrás ver el estado de tu inscripción y tu boleta asignada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>