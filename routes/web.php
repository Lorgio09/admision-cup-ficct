<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\DocenteController;
use Illuminate\Support\Facades\Route;

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

// ==========================================
// RUTAS DEL PANEL (Requieren verificación)
// ==========================================
Route::get('/dashboard', function () {
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
});

require __DIR__.'/auth.php';