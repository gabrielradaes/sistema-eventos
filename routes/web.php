<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/eventos/inscribir', [EventController::class, 'enroll'])->name('events.enroll');

Route::get('/dashboard', function () {
    // Obtenemos todos los datos necesarios para ambas vistas (Admin y Funcionario)
    $events = \App\Models\Event::with('auditorium')->get();
    $auditoriums = \App\Models\Auditorium::all();
    $equipments = \App\Models\Equipment::all();
    
    return view('dashboard', compact('events', 'auditoriums', 'equipments'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta para que el funcionario envíe su solicitud
Route::post('/eventos/solicitar', [EventController::class, 'requestEvent'])->name('events.request')->middleware('auth');

// --- RUTAS PROTEGIDAS PARA EL SISTEMA ---
Route::middleware('auth')->group(function () {
    // 1. Mostrar el formulario de creación
    Route::get('/eventos/crear', [EventController::class, 'create'])->name('events.create');
    
    // 2. Procesar el formulario y guardar en MySQL
    Route::post('/eventos', [EventController::class, 'store'])->name('events.store');

    // Rutas de perfil (vienen por defecto con Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('events.my');

    // Mostrar formulario de edición
    Route::get('/eventos/{event}/editar', [EventController::class, 'edit'])->name('events.edit');
    
    // Procesar la actualización
    Route::put('/eventos/{event}', [EventController::class, 'update'])->name('events.update');

    Route::get('/cursos-disponibles', [EventController::class, 'availableCourses'])->name('events.available');

    // Bandeja de solicitudes (Solo Admin)
    Route::get('/solicitudes', [EventController::class, 'requests'])->name('events.requests');
    
    // Acciones de Aprobar y Rechazar
    Route::patch('/solicitudes/{event}/aprobar', [EventController::class, 'approve'])->name('events.approve');
    Route::patch('/solicitudes/{event}/rechazar', [EventController::class, 'reject'])->name('events.reject');
});

require __DIR__.'/auth.php';
