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
            ];
        });

        return response()->json($events);
    }

    // Guardar la reserva desde el modal
    public function store(Request $request)
    {
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

    // Mostrar la vista del formulario de protocolo detallado
    public function protocolForm($id)
    {
        $event = Event::findOrFail($id);
        return view('salones.protocolo', compact('event'));
    }

    // Guardar los datos del formulario detallado y redirigir al calendario
    public function updateProtocol(Request $request, $id)
    {
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
}