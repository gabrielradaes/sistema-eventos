<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// GRUPO PROTEGIDO: Todo lo que esté aquí adentro requiere haber iniciado sesión
Route::middleware('auth')->group(function () {
    
    // === GESTIÓN DE USUARIOS ===
    Route::get('/salones/usuarios', function () {
        $users = \App\Models\User::all();
        return view('salones.usuarios', compact('users'));
    })->name('salones.usuarios');


    // === PAPELERA ===
    Route::get('/salones/papelera', [EventController::class, 'papelera'])->name('salones.papelera');
    Route::delete('/salones/papelera/vaciar', [EventController::class, 'vaciarPapelera'])->name('salones.vaciar_papelera');
    Route::post('/salones/papelera/{id}/restaurar', [EventController::class, 'restaurarPapelera'])->name('salones.restaurar');

    Route::post('/salones/usuarios', [App\Http\Controllers\UserController::class, 'store'])->name('usuarios.store');
    Route::put('/salones/usuarios/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/salones/usuarios/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('usuarios.destroy');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === VISTAS DE SALONES ===
    Route::get('/salones/usar', function () {
        return view('salones.usar');
    })->name('salones.usar');

    // AQUÍ ESTÁ TU NUEVA RUTA DE SUPERVISAR
    Route::get('/salones/supervisar', [EventController::class, 'supervisar'])->name('salones.supervisar');

    // === RUTAS DEL FORMULARIO DE PROTOCOLO ===
    Route::get('/salones/reservas/{id}/protocolo', [EventController::class, 'protocolForm'])->name('salones.protocolo');
    Route::put('/salones/reservas/{id}/protocolo', [EventController::class, 'updateProtocol'])->name('salones.protocolo.update');

    // === API DE EVENTOS (FullCalendar) ===
    Route::get('/api/salones/events', [EventController::class, 'getEvents'])->name('api.salones.events');
    Route::post('/api/salones/events', [EventController::class, 'store'])->name('api.salones.store');
    Route::put('/api/salones/events/{id}', [EventController::class, 'update']);
    Route::delete('/api/salones/events/{id}', [EventController::class, 'destroy']);

    // === API DEL MINI CALENDARIO ===
    Route::get('/api/events/dates', function (\Illuminate\Http\Request $request) {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        try {
            $dates = \App\Models\Event::whereMonth('start_time', $month)
                ->whereYear('start_time', $year)
                ->selectRaw('DATE(start_time) as date')
                ->distinct()
                ->pluck('date');
            return response()->json($dates);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    });

}); // Fin del grupo protegido

require __DIR__.'/auth.php';