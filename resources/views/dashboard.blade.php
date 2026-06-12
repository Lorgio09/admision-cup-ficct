<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-8 rounded-2xl shadow-sm font-bold flex items-center gap-3">
                    <span class="text-xl">✅</span> {{ session('status') }}
                </div>
            @endif

            @if(Auth::user()->rol === 'admin')
                <div class="bg-white overflow-hidden shadow-xl shadow-slate-200/50 sm:rounded-3xl border border-slate-100">
                    <div class="p-8 sm:p-10 text-slate-900">
                        
                        <div class="mb-10">
                            <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                            <p class="text-slate-500 mt-2 text-base max-w-2xl">Has iniciado sesión como <strong class="text-slate-700">Administrador</strong>. Desde aquí puedes gestionar los catálogos del sistema, asignar cupos y registrar nuevo personal.</p>
                        </div>

                        @if(isset($kpisDashboard))
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 group cursor-default">
                                    <div>
                                        <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Total Inscritos</p>
                                        <p class="text-3xl font-black text-slate-800">{{ $kpisDashboard['total'] }}</p>
                                    </div>
                                    <div class="text-2xl bg-indigo-50 text-indigo-600 p-3 rounded-2xl group-hover:scale-110 group-hover:bg-indigo-100 transition-all">👥</div>
                                </div>

                                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 group cursor-default">
                                    <div>
                                        <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Aprobados</p>
                                        <p class="text-3xl font-black text-slate-800">{{ $kpisDashboard['aprobados'] }}</p>
                                    </div>
                                    <div class="text-2xl bg-emerald-50 text-emerald-600 p-3 rounded-2xl group-hover:scale-110 group-hover:bg-emerald-100 transition-all">✅</div>
                                </div>

                                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 group cursor-default">
                                    <div>
                                        <p class="text-[11px] font-bold text-rose-500 uppercase tracking-widest mb-1">Reprobados</p>
                                        <p class="text-3xl font-black text-slate-800">{{ $kpisDashboard['reprobados'] }}</p>
                                    </div>
                                    <div class="text-2xl bg-rose-50 text-rose-600 p-3 rounded-2xl group-hover:scale-110 group-hover:bg-rose-100 transition-all">❌</div>
                                </div>
                        
                                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 group cursor-default">
                                    <div>
                                        <p class="text-[11px] font-bold text-purple-500 uppercase tracking-widest mb-1">Grupos Activos</p>
                                        <p class="text-3xl font-black text-slate-800">{{ $kpisDashboard['grupos'] }}</p>
                                    </div>
                                    <div class="text-2xl bg-purple-50 text-purple-600 p-3 rounded-2xl group-hover:scale-110 group-hover:bg-purple-100 transition-all">🏫</div>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($gestionActiva))
                            <div class="mb-8 bg-emerald-50/50 border border-emerald-100 rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 transition-colors hover:bg-emerald-50">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                                    <div class="text-3xl bg-white p-4 rounded-2xl shadow-sm border border-emerald-100 text-emerald-500">📖</div>
                                    <div class="mt-1">
                                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Semestre Activo</p>
                                        <h4 class="text-2xl sm:text-3xl font-black text-emerald-950">{{ $gestionActiva->nombre }}</h4>
                                        <p class="text-emerald-700/80 text-sm mt-1">Este periodo está habilitado y recibiendo inscripciones.</p>
                                    </div>
                                </div>
                                <div class="w-full md:w-auto mt-2 md:mt-0">
                                    <a href="{{ route('gestiones.edit', $gestionActiva->id) }}" class="flex justify-center items-center gap-2 bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 font-bold py-3 px-6 rounded-xl shadow-sm text-sm transition-all w-full md:w-auto">
                                        ✏️ Editar Gestión y Cupos
                                    </a>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 rounded-3xl border border-slate-100 p-6 sm:p-8 mb-8">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 border-b border-slate-200/60 pb-3">Acciones de Cierre de Semestre</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <form action="{{ route('gestiones.procesar', $gestionActiva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto calculará los ingresos y descontará los cupos de manera definitiva basándose en las notas más altas.');">
                                        @csrf
                                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex justify-center items-center gap-3 group">
                                            <span class="text-xl group-hover:rotate-180 transition-transform duration-500">⚙️</span> 
                                            Calcular y Asignar Ingresos
                                        </button>
                                    </form>

                                    <a href="{{ route('admisiones.resultados') }}" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex justify-center items-center gap-3">
                                        <span class="text-xl">📊</span> 
                                        Ver Lista Oficial de Admitidos
                                    </a>
                                </div>
                            </div>
                            
                        @else
                            <div class="mb-10 bg-amber-50 border border-amber-200 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center text-center sm:text-left gap-5">
                                <div class="text-4xl bg-white p-4 rounded-2xl shadow-sm border border-amber-100">⚠️</div>
                                <div>
                                    <h4 class="text-xl font-black text-amber-900 mb-1">Sistema en Pausa</h4>
                                    <p class="text-amber-700 text-sm">No hay ningún semestre activo en este momento. Los estudiantes no podrán inscribirse hasta que apertures uno.</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="p-6 sm:p-8 bg-white rounded-3xl shadow-sm border border-slate-200 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-6 group hover:border-blue-300 transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Gestión Académica Futura</h3>
                                <p class="text-slate-500 text-sm mt-1">Crea un nuevo periodo de inscripción. Al hacerlo, el semestre actual se cerrará automáticamente.</p>
                            </div>
                            <div class="w-full sm:w-auto">
                                <a href="{{ route('gestiones.create') }}" class="w-full sm:w-auto bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-600 hover:text-white hover:border-blue-600 font-bold py-3.5 px-6 rounded-xl transition-all flex justify-center items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Aperturar Nuevo Semestre
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            @elseif(Auth::user()->rol === 'docente')
                <div class="bg-white overflow-hidden shadow-xl shadow-slate-200/50 sm:rounded-3xl border border-slate-100">
                    <div class="p-8 sm:p-10 text-slate-900">
                        <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                        <p class="text-slate-600">Has iniciado sesión como <strong class="text-slate-800">Docente</strong>.</p>
                        <p class="mt-4 text-slate-500 bg-slate-50 p-4 rounded-2xl border border-slate-100">Próximamente aquí podrás ver las materias que te han sido asignadas y registrar las calificaciones de tus postulantes.</p>
                    </div>
                </div>

            @elseif(Auth::user()->rol === 'postulante' && isset($postulante))
                
                @if($postulante->estado === 'ADMITIDO')
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl shadow-xl shadow-emerald-200/50 p-10 text-center text-white mb-8 transform hover:scale-[1.02] transition-transform duration-300">
                        <div class="text-7xl mb-6">🎉</div>
                        <h2 class="text-4xl font-black mb-3 tracking-tight">¡Felicidades, {{ $postulante->nombre }}!</h2>
                        <p class="text-xl text-emerald-50">Has sido <strong>ADMITIDO</strong> oficialmente en la carrera de:</p>
                        <div class="mt-6 inline-block bg-white text-emerald-700 font-black text-2xl md:text-3xl px-8 py-4 rounded-2xl shadow-lg border-2 border-emerald-400">
                            {{ $postulante->carreraAdmitida->nombre ?? 'No especificada' }}
                        </div>
                        <p class="mt-8 text-emerald-100 font-bold tracking-widest uppercase text-sm">Promedio Final: <span class="text-white text-lg">{{ $postulante->promedio }} / 100</span></p>
                    </div>

                @elseif($postulante->estado === 'APROBADO_SIN_CUPO')
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl shadow-md p-10 text-center mb-8">
                        <div class="text-6xl mb-4">⚠️</div>
                        <h2 class="text-3xl font-black text-amber-900 mb-3 tracking-tight">Aprobaste, pero no hay cupos</h2>
                        <p class="text-amber-700 max-w-2xl mx-auto text-lg">Tu promedio final es de <strong>{{ $postulante->promedio }}</strong>. Aprobaste los exámenes, pero lamentablemente los cupos de tus opciones de carrera se llenaron con promedios más altos.</p>
                    </div>

                @elseif($postulante->estado === 'REPROBADO')
                    <div class="bg-rose-50 border border-rose-200 rounded-3xl shadow-md p-10 text-center mb-8">
                        <div class="text-6xl mb-4">😔</div>
                        <h2 class="text-3xl font-black text-rose-900 mb-3 tracking-tight">No alcanzaste la nota mínima</h2>
                        <p class="text-rose-700 max-w-2xl mx-auto text-lg">Tu promedio final fue de <strong>{{ $postulante->promedio }}</strong>. El puntaje mínimo de aprobación es 60. Te deseamos éxito en la próxima gestión.</p>
                    </div>
                
                @elseif($postulante->estado === 'APROBADO')
                    <div class="bg-indigo-50 border border-indigo-200 rounded-3xl shadow-md p-10 text-center mb-8">
                        <div class="text-6xl mb-4">⏳</div>
                        <h2 class="text-3xl font-black text-indigo-900 mb-3 tracking-tight">¡Exámenes finalizados!</h2>
                        <p class="text-indigo-700 max-w-2xl mx-auto text-lg">Tu promedio final es <strong>{{ $postulante->promedio }}</strong>. Estamos procesando la asignación de cupos a nivel general. Vuelve a revisar este panel pronto para ver si fuiste admitido.</p>
                    </div>

                @else
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-slate-300 transition-colors">
                        <div class="flex flex-col md:flex-row items-center gap-5 text-center md:text-left">
                            <div class="bg-indigo-50 text-indigo-600 p-5 rounded-2xl text-4xl shadow-sm border border-indigo-100">🎓</div>
                            <div>
                                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">¡Bienvenido, {{ $postulante->nombre }}!</h3>
                                <p class="text-slate-500 mt-1">Tu inscripción al Curso Preuniversitario (CUP) está confirmada.</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-8 py-5 rounded-2xl border border-slate-100 text-center md:text-right w-full md:w-auto shadow-inner">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tu Asignación Física</p>
                            <p class="text-2xl font-black text-indigo-950">{{ $postulante->grupo->nombre ?? 'Sin grupo' }}</p>
                            <div class="flex gap-2 justify-center md:justify-end mt-3">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">Turno: {{ $postulante->grupo->turno ?? 'N/A' }}</span>
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">Aula: {{ $postulante->grupo->aula->nombre ?? 'Por definir' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-6 px-2 border-b border-slate-200 pb-3">
                        <span class="text-xl">📚</span>
                        <h3 class="text-xl font-extrabold text-slate-800">Mis Materias y Docentes</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @if($postulante->grupo && $postulante->grupo->asignaciones->count() > 0)
                            @foreach($postulante->grupo->asignaciones as $asignacion)
                                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 hover:shadow-lg transition-all duration-300 group cursor-default">
                                    <div class="flex items-center gap-4 border-b border-slate-50 pb-4 mb-4">
                                        <div class="bg-blue-50 text-blue-600 p-3 rounded-xl text-xl shadow-sm group-hover:scale-110 transition-transform">📘</div>
                                        <h4 class="font-bold text-slate-800 leading-tight">{{ $asignacion->materia->nombre }}</h4>
                                    </div>
                                    <div class="mb-5">
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Docente Titular</p>
                                        <p class="text-sm font-semibold text-slate-900 mt-1">{{ $asignacion->docente->nombre }}</p>
                                    </div>
                                    <div class="bg-slate-50 rounded-xl p-4 text-center border border-slate-100 shadow-inner group-hover:bg-slate-100 transition-colors">
                                        <p class="text-xs text-slate-500 font-medium mb-1">Calificación</p>
                                        <p class="text-xl font-black text-slate-300">-- / 100</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white p-10 text-center rounded-3xl border border-slate-100 shadow-sm text-slate-500">
                                <div class="text-5xl mb-3">⏳</div>
                                <p class="text-lg font-medium">Aún no se han asignado docentes a tu grupo.</p>
                            </div>
                        @endif
                    </div>
                @endif
            @endif

        </div>
    </div>

    @if(Auth::user()->rol === 'admin')
        <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3">
            <div id="voice-status" class="bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-lg hidden animate-pulse">
                🎙️ Escuchando comando...
            </div>
            <button id="btn-voice" onclick="activarAsistenteVoz()" class="bg-gradient-to-br from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white p-4 rounded-full shadow-xl shadow-indigo-200 transition-all transform hover:scale-110 flex items-center justify-center border-2 border-white focus:outline-none focus:ring-4 focus:ring-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
            </button>
        </div>

        <script>
            function activarAsistenteVoz() {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                
                if (!SpeechRecognition) {
                    alert("Tu navegador no soporta el reconocimiento de voz. Te recomendamos usar Google Chrome.");
                    return;
                }

                const recognition = new SpeechRecognition();
                recognition.lang = 'es-BO'; 
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                const statusDiv = document.getElementById('voice-status');
                const btnVoice = document.getElementById('btn-voice');

                recognition.onstart = () => {
                    statusDiv.classList.remove('hidden');
                    btnVoice.classList.add('ring-4', 'ring-indigo-300', 'scale-110');
                };

                recognition.onend = () => {
                    statusDiv.classList.add('hidden');
                    btnVoice.classList.remove('ring-4', 'ring-indigo-300', 'scale-110');
                };

                recognition.onresult = (event) => {
                    const textoEscuchado = event.results[0][0].transcript.toLowerCase();
                    console.log("Comando recibido: " + textoEscuchado);

                    if (textoEscuchado.includes('reportes') || textoEscuchado.includes('estadísticas') || textoEscuchado.includes('ver reportes')) {
                        alert('🤖 Asistente: Abriendo Módulo de Reportes...');
                        window.location.href = "{{ route('reportes.index') }}";
                    }
                    else if (textoEscuchado.includes('admitidos') || textoEscuchado.includes('resultados') || textoEscuchado.includes('lista oficial')) {
                        alert('🤖 Asistente: Redirigiendo a la Lista de Admitidos...');
                        window.location.href = "{{ route('admisiones.resultados') }}";
                    }
                    else if (textoEscuchado.includes('postulantes') || textoEscuchado.includes('inscritos') || textoEscuchado.includes('ver postulantes')) {
                        alert('🤖 Asistente: Abriendo Lista de Postulantes...');
                        window.location.href = "{{ route('postulantes.index') }}";
                    }
                    else if (textoEscuchado.includes('descargar pdf') || textoEscuchado.includes('exportar pdf') || textoEscuchado.includes('imprimir acta')) {
                        alert('🤖 Asistente: Generando y descargando Acta de Resultados en PDF...');
                        window.location.href = "{{ route('reportes.pdf', ['gestion_id' => $gestionActiva->id ?? '']) }}";
                    }
                    else if (textoEscuchado.includes('descargar excel') || textoEscuchado.includes('exportar excel') || textoEscuchado.includes('bajar excel')) {
                        alert('🤖 Asistente: Generando y descargando reporte en formato Excel...');
                        window.location.href = "{{ route('reportes.excel', ['gestion_id' => $gestionActiva->id ?? '']) }}";
                    }
                    else {
                        alert('🤖 Asistente: Comando "' + textoEscuchado + '" no reconocido. Intenta con: "Ver reportes", "Descargar PDF", "Ver admitidos" o "Ver postulantes".');
                    }
                };

                recognition.onerror = (event) => {
                    console.error(event.error);
                    statusDiv.classList.add('hidden');
                };

                recognition.start();
            }
        </script>
    @endif
</x-app-layout>