<?php

use Illuminate\Http\Request;

use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\SeleccionGrupoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\Admin\GestionController;
use App\Http\Controllers\Admin\ReporteAdmisionController;
use Illuminate\Support\Facades\Route;
use App\Models\Postulante;
use App\Models\Gestion;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// RUTAS PÚBLICAS (No requieren iniciar sesión)
// ==========================================
// Formulario de inscripción
Route::get('/inscripcion', [PostulanteController::class, 'create'])->name('postulantes.create');
Route::post('/inscripcion', [PostulanteController::class, 'store'])->name('postulantes.store');

// Pasarela de pago con Tarjeta (Stripe) - ¡Sin el parámetro!
Route::get('/inscripcion/pago', [PostulanteController::class, 'checkout'])->name('postulantes.checkout');
Route::post('/inscripcion/pago/procesar', [PostulanteController::class, 'procesarPago'])->name('postulantes.procesarPago');

// AQUÍ DEBEN ESTAR LAS RUTAS DE CONSULTA (Totalmente libres del middleware auth)
Route::get('/consulta-cup', [ConsultaController::class, 'index'])->name('consulta.index');
Route::post('/consulta-cup/buscar', [ConsultaController::class, 'buscar'])->name('consulta.buscar');
Route::post('/consulta-cup/{postulante}/pagar', [ConsultaController::class, 'pagar'])->name('consulta.pagar');

// ==========================================
// RUTAS DEL PANEL (Requieren verificación)
// ==========================================
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->user();
    $gestionActiva = \App\Models\Gestion::where('is_active', true)->first();

    // ==========================================
    // 1. LÓGICA PARA POSTULANTES Y DOCENTES
    // ==========================================
    if ($user->rol !== 'admin') {
        $postulante = \App\Models\Postulante::where('correo', $user->email)->first();

        // Si es postulante y no tiene grupo, lo mandamos a elegir
        if ($postulante && empty($postulante->grupo_id)) {
            return redirect()->route('grupo.seleccion');
        }

        // Si es postulante y YA tiene grupo, cargamos toda su información académica
        if ($user->rol === 'postulante' && $postulante) {
            // Eager loading: Traemos su grupo, el aula física, y a los docentes asignados
            $postulante->load(['grupo.aula', 'grupo.asignaciones.materia', 'grupo.asignaciones.docente']);
            return view('dashboard', compact('gestionActiva', 'postulante'));
        }

        // Si es Docente, evitamos la lógica de arriba y va directo a su vista básica
        return view('dashboard', compact('gestionActiva'));
    }

    // ==========================================
    // 2. LÓGICA EXCLUSIVA PARA ADMINISTRADORES
    // ==========================================
    $kpisDashboard = null;

    // Solo calculamos los indicadores si existe un semestre activo
    if ($gestionActiva) {
        $kpisDashboard = [
            'total'      => \App\Models\Postulante::whereHas('grupo', function($q) use ($gestionActiva) {
                                $q->where('gestion_id', $gestionActiva->id);
                            })->count(),
                            
            'aprobados'  => \App\Models\Postulante::whereHas('grupo', function($q) use ($gestionActiva) {
                                $q->where('gestion_id', $gestionActiva->id);
                            })->where('promedio', '>=', 60)->count(),
                            
            'reprobados' => \App\Models\Postulante::whereHas('grupo', function($q) use ($gestionActiva) {
                                $q->where('gestion_id', $gestionActiva->id);
                            })->whereNotNull('promedio')->where('promedio', '<', 60)->count(),
                            
            'grupos'     => \App\Models\Grupo::where('gestion_id', $gestionActiva->id)->count()
        ];
    }

    // Retorna la vista del administrador con sus indicadores y el asistente de voz listo
    return view('dashboard', compact('gestionActiva', 'kpisDashboard'));

})->middleware(['auth', 'verified'])->name('dashboard');
// ==========================================
// RUTAS PRIVADAS (Requieren iniciar sesión)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas del Postulante para el Administrador (le quitamos las de crear porque son públicas)
    Route::resource('postulantes', PostulanteController::class)->except(['create', 'store']);
    
    // Catálogos del sistema
    Route::resource('carreras', CarreraController::class);
    Route::resource('materias', MateriaController::class);
    Route::resource('docentes', DocenteController::class);

    Route::post('/postulantes/{postulante}/aprobar', [PostulanteController::class, 'aprobar'])->name('postulantes.aprobar');

    // Módulo de Exámenes
    Route::get('/postulantes/{postulante}/evaluar', [PostulanteController::class, 'evaluar'])->name('postulantes.evaluar');
    Route::post('/postulantes/{postulante}/calificar', [PostulanteController::class, 'calificar'])->name('postulantes.calificar');
    // Módulo de Asignación de Grupos
    Route::get('/grupos', [\App\Http\Controllers\GrupoController::class, 'index'])->name('grupos.index');
    Route::post('/grupos', [\App\Http\Controllers\GrupoController::class, 'store'])->name('grupos.store');
    Route::post('/grupos/generar', [\App\Http\Controllers\GrupoController::class, 'generar'])->name('grupos.generar');
    Route::delete('/grupos/{grupo}', [\App\Http\Controllers\GrupoController::class, 'destroy'])->name('grupos.destroy'); 

    Route::get('/seleccionar-grupo', [SeleccionGrupoController::class, 'index'])->name('grupo.seleccion');
    Route::post('/seleccionar-grupo', [SeleccionGrupoController::class, 'store'])->name('grupo.asignar');

    Route::get('/admin/gestiones/crear', [GestionController::class, 'create'])->name('gestiones.create');
    Route::post('/admin/gestiones', [GestionController::class, 'store'])->name('gestiones.store');

    Route::post('/asignaciones', [AsignacionController::class, 'store'])->name('asignaciones.store');
    Route::delete('/asignaciones/{asignacion}', [AsignacionController::class, 'destroy'])->name('asignaciones.destroy');

    Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('calificaciones.index');
    Route::get('/calificaciones/planilla/{asignacion}', [CalificacionController::class, 'planilla'])->name('calificaciones.planilla');
    Route::post('/calificaciones/planilla/{asignacion}', [App\Http\Controllers\CalificacionController::class, 'guardar'])->name('calificaciones.guardar');

    // Rutas para administrar la Gestión y los Cupos
    Route::get('/gestiones/{gestion}/editar', [App\Http\Controllers\Admin\GestionController::class, 'edit'])->name('gestiones.edit');
    Route::put('/gestiones/{gestion}', [App\Http\Controllers\Admin\GestionController::class, 'update'])->name('gestiones.update');

    Route::post('/gestiones/{gestion}/procesar-admisiones', [App\Http\Controllers\Admin\GestionController::class, 'procesarAdmisiones'])->name('gestiones.procesar');

    Route::get('/admisiones/resultados', [ReporteAdmisionController::class, 'index'])->name('admisiones.resultados');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.pdf');

    Route::get('/reportes/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.excel');
});

require __DIR__.'/auth.php';