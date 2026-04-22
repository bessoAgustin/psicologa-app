<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\DisponibilidadController;
use Inertia\Inertia;
use App\Http\Controllers\DiaBloqueadoController;
use App\Http\Controllers\PagoController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// Rutas para pacientes autenticados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index']);
    Route::get('/sesiones-mes', [SesionController::class, 'sesionesMesGlobal']);
    Route::get('/sesiones-dia', [SesionController::class, 'sesionesDiaGlobal']);
    Route::post('/sesiones', [SesionController::class, 'storeGlobal']);
    Route::post('/pago/crear', [PagoController::class, 'crear'])->name('pago.crear');
    Route::get('/confirmacion', [PagoController::class, 'confirmar']);
    Route::get('/mis-citas', [SesionController::class, 'misCitas'])->name('citas.index');
    Route::get('/mis-citas/{sesion}', [SesionController::class, 'detalleCita'])->name('citas.show');
    Route::post('/mis-citas/{sesion}/cancelar', [SesionController::class, 'cancelarCita'])->name('citas.cancelar');
    Route::get('/dias-bloqueados', [DiaBloqueadoController::class, 'index']);
});

// Rutas solo para admin (psicóloga)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('pacientes', \App\Http\Controllers\PacienteController::class);

    Route::post('/dias-bloqueados', [DiaBloqueadoController::class, 'store']);
    Route::delete('/dias-bloqueados/{fecha}', [DiaBloqueadoController::class, 'destroy']);

    Route::get('/agenda', function () {
        return Inertia::render('Agenda/Index');
    });

    Route::get('/todas-las-citas', [SesionController::class, 'todasLasCitas'])->name('citas.todas');
});

require __DIR__.'/settings.php';