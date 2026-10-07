<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // API para alimentar el calendario con los eventos existentes
    public function getEvents(Request $request)
    {
        $events = Event::all()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_time,
                'end' => $event->end_time,
                'backgroundColor' => '#f59e0b', // Color naranja de reserva
                'borderColor' => '#f59e0b',
                // Agregamos los datos extendidos para que el modal de edición los lea
                'user_name' => $event->user_name,
                'user_phone' => $event->user_phone,
                'authority_name' => $event->authority_name,
                'responsible' => $event->responsible,
                'salon' => $event->salon,
                'created_at' => $event->created_at,
            ];
        });

        return response()->json($events);
    }

    // Mostrar el panel administrativo de Supervisar
    public function supervisar()
    {
        // Traemos todos los eventos ordenados por fecha de inicio (del más reciente al más antiguo)
        $events = Event::orderBy('start_time', 'desc')->get();
        return view('salones.supervisar', compact('events'));
    }

    // Guardar la nueva reserva desde el modal inicial
    public function store(Request $request)
    {
        if (auth()->user()->role === 'usuario' || empty(auth()->user()->role)) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para crear.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'nullable|string',
            'user_phone' => 'nullable|string',
            'authority_name' => 'nullable|string',
            'responsible' => 'nullable|string',
            'salon' => 'required|string',
        ]);

        $event = Event::create($validated);

        // Retornamos la URL de redirección al formulario detallado de protocolo
        return response()->json([
            'success' => true, 
            'redirect_url' => route('salones.protocolo', $event->id)
        ]);
    }

    // ACTUALIZAR RÁPIDO: Guardar cambios hechos desde el modal flotante de "Editar reserva"
    public function update(Request $request, $id)
    {
        if (auth()->user()->role === 'usuario' || empty(auth()->user()->role)) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para editar.'], 403);
        }

        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_name' => 'nullable|string',
            'user_phone' => 'nullable|string',
            'authority_name' => 'nullable|string',
            'responsible' => 'nullable|string',
            'salon' => 'required|string',
        ]);

        $event->update($validated);

        return response()->json(['success' => true]);
    }

    // ELIMINAR: Borrar la reserva con el botón del basurero
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Solo el Admin puede borrar reservas.'], 403);
        }

        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['success' => true]);
    }

    // Mostrar la vista del formulario de protocolo detallado
    public function protocolForm($id)
    {
        $event = Event::findOrFail($id);
        return view('salones.protocolo', compact('event'));
    }

    // Guardar los datos del formulario detallado (vista completa) y redirigir al calendario
    public function updateProtocol(Request $request, $id)
    {

        if (auth()->user()->role === 'usuario' || empty(auth()->user()->role)) {
            abort(403, 'No tienes permiso para modificar los protocolos.');
        }

        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'authority_name' => 'nullable|string',
            'event_type' => 'nullable|string',
            'requirements' => 'nullable|array',
            'capacity' => 'nullable|integer',
            'entry_type' => 'nullable|string',
            'external_coordinator_name' => 'nullable|string',
            'external_coordinator_phone' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'registered_by' => 'nullable|string',
            'internal_coordinator' => 'nullable|string',
        ]);

        $event->update($validated);

        return redirect()->route('salones.usar')->with('success', 'Reserva completada con éxito');
    }
    // Mostrar la vista de la papelera (Solo Admin)
    public function papelera()
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');
        
        // Trae SOLO los eventos que fueron borrados (escondidos)
        $events = Event::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('salones.papelera', compact('events'));
    }

    // Vaciar la papelera destruyendo los datos para siempre (Solo Admin)
    public function vaciarPapelera()
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Acceso denegado.');
        
        Event::onlyTrashed()->forceDelete();
        return back();
    }
    // Restaurar una reserva desde la papelera (Admin y Superadmin)
    public function restaurarPapelera($id)
    {
        if (auth()->user()->role === 'usuario' || empty(auth()->user()->role)) {
            abort(403, 'Acceso denegado.');
        }

        // Buscar en los borrados y restaurarlo
        $event = Event::onlyTrashed()->findOrFail($id);
        $event->restore();

        return back(); // Te devuelve a la papelera automáticamente
    }
}