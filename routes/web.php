<?php

use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\SeleccionGrupoController;
use Illuminate\Support\Facades\Route;
use App\Models\Postulante;

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
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    // Si el usuario es un estudiante (no admin)
    if ($user->rol !== 'admin') {
        
        // CORRECCIÓN AQUÍ: Columna 'correo' de postulantes vs Propiedad 'email' de users
        $postulante = \App\Models\Postulante::where('correo', $user->email)->first();

        if ($postulante && empty($postulante->grupo_id)) {
            return redirect()->route('grupo.seleccion');
        }
    }

    // Si ya tiene grupo (o es admin), entra normal a su panel
    return view('dashboard');
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
});

require __DIR__.'/auth.php';