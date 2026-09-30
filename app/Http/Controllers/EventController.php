<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditorium;
use App\Models\Event;
use App\Models\Equipment;

class EventController extends Controller
{
    // Muestra la vista con el formulario
    public function create()
    {
        $auditoriums = Auditorium::all();
        $equipments = Equipment::all(); // Agregado
        return view('events.create', compact('auditoriums', 'equipments'));
    }

    // Recibe los datos, los valida y los guarda
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'instructor_name' => 'required|string|max:255',
            'auditorium_id' => 'required|exists:auditoriums,id',
            'capacity' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'equipment' => 'nullable|array', 
            'description' => 'nullable|string',
        ]);

        // 1. Validación de Auditorio (La que ya tenías)
        $hasOverlap = Event::where('auditorium_id', $request->auditorium_id)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($hasOverlap) {
            return back()->withErrors(['time_error' => 'El auditorio ya está reservado en este horario.'])->withInput();
        }

        // 2. VALIDACIÓN DE INVENTARIO FÍSICO
        if ($request->has('equipment')) {
            foreach ($request->equipment as $equip_id => $quantity_requested) {
                if ($quantity_requested > 0) {
                    $item = Equipment::findOrFail($equip_id);
                    
                    // Sumamos cuántos de este ítem se están usando en otros eventos que chocan en tiempo
                    $usedQuantity = \DB::table('equipment_event')
                        ->join('events', 'equipment_event.event_id', '=', 'events.id')
                        ->where('equipment_event.equipment_id', $equip_id)
                        ->where('events.start_time', '<', $request->end_time)
                        ->where('events.end_time', '>', $request->start_time)
                        ->sum('equipment_event.quantity_reserved');

                    // Verificamos si queda stock
                    if (($usedQuantity + $quantity_requested) > $item->total_quantity) {
                        return back()->withErrors([
                            'stock_error' => "No hay suficientes {$item->name} disponibles en ese horario. Solo quedan " . ($item->total_quantity - $usedQuantity) . " libres."
                        ])->withInput();
                    }
                }
            }
        }

        // 3. Guardar Evento y Asociar Equipos
        $event = Event::create($validatedData);

        if ($request->has('equipment')) {
            foreach ($request->equipment as $equip_id => $quantity_requested) {
                if ($quantity_requested > 0) {
                    $event->equipment()->attach($equip_id, ['quantity_reserved' => $quantity_requested]);
                }
            }
        }

        return redirect()->route('dashboard')->with('status', '¡Curso creado exitosamente con sus recursos asignados!');
    }
    public function enroll(Request $request)
    {
        $request->validate(['event_id' => 'required|exists:events,id']);
        
        $user = auth()->user();
        $event = Event::withCount('users')->findOrFail($request->event_id);

        // 1. Validar que el usuario no esté ya inscrito
        if ($user->events()->where('event_id', $event->id)->exists()) {
            return back()->withErrors(['enroll_error' => 'Ya te encuentras inscrito en este curso.']);
        }

        // 2. Validar límite de capacidad
        if ($event->users_count >= $event->capacity) {
            return back()->withErrors(['enroll_error' => 'Lo sentimos, este curso ya no tiene cupos disponibles.']);
        }

        // 3. Validar choques de horario en la agenda personal del funcionario
        $hasOverlap = $user->events()
            ->where('start_time', '<', $event->end_time)
            ->where('end_time', '>', $event->start_time)
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['enroll_error' => 'No puedes inscribirte. Ya tienes otro curso que choca con este horario.']);
        }

        // Si pasa todas las validaciones, lo inscribimos de forma segura
        $user->events()->attach($event->id);

        return back()->with('status', '¡Inscripción exitosa al curso: ' . $event->title . '!');
    }
    public function myEvents()
    {
        // Traemos solo los eventos donde el usuario logueado está inscrito
        $myEvents = auth()->user()->events()->with('auditorium')->orderBy('start_time', 'asc')->get();

        return view('events.my_events', compact('myEvents'));
    }
    public function edit(Event $event)
    {
        $auditoriums = Auditorium::all();
        // Redirigimos a una nueva vista pasando el evento actual y los auditorios disponibles
        return view('events.edit', compact('event', 'auditoriums'));
    }

    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'auditorium_id' => 'required|exists:auditoriums,id',
            'capacity' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Validación Lógica: Evitar choques (EXCLUYENDO el evento actual)
        $hasOverlap = Event::where('auditorium_id', $request->auditorium_id)
            ->where('id', '!=', $event->id) // Ignorar este mismo evento en la comprobación
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['time_error' => 'El auditorio ya está reservado en este horario.'])->withInput();
        }

        $event->update($validatedData);

        return redirect()->route('dashboard')->with('status', '¡Curso actualizado correctamente!');
    }
    public function availableCourses()
    {
        $events = Event::with(['auditorium', 'users'])
            ->where('status', 'aprobado') // Garantizamos que solo vea los aprobados
            ->where('start_time', '>=', today()) // Cambiamos now() por today() para incluir todos los de hoy
            ->orderBy('start_time', 'asc')
            ->get();

        return view('events.available', compact('events'));
    }

    public function requestEvent(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'support_staff' => 'nullable|string|max:255', // Nuevo campo de apoyo
            'auditorium_id' => 'required|exists:auditoriums,id',
            'capacity' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'equipment' => 'nullable|array',
        ]);

        // 1. Validar que el espacio no esté ocupado ni solicitado por alguien más
        $hasOverlap = Event::where('auditorium_id', $request->auditorium_id)
            ->whereIn('status', ['aprobado', 'pendiente']) // Protegemos eventos aprobados y en revisión
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($hasOverlap) {
            return back()->withErrors(['time_error' => 'El auditorio ya está reservado o en proceso de revisión en este horario.'])->withInput();
        }

        // 2. Validar el inventario físico compartido
        if ($request->has('equipment')) {
            foreach ($request->equipment as $equip_id => $quantity_requested) {
                if ($quantity_requested > 0) {
                    $item = \App\Models\Equipment::findOrFail($equip_id);
                    
                    $usedQuantity = \DB::table('equipment_event')
                        ->join('events', 'equipment_event.event_id', '=', 'events.id')
                        ->where('equipment_event.equipment_id', $equip_id)
                        ->whereIn('events.status', ['aprobado', 'pendiente'])
                        ->where('events.start_time', '<', $request->end_time)
                        ->where('events.end_time', '>', $request->start_time)
                        ->sum('equipment_event.quantity_reserved');

                    if (($usedQuantity + $quantity_requested) > $item->total_quantity) {
                        return back()->withErrors([
                            'stock_error' => "Stock insuficiente de {$item->name}. Solo quedan " . ($item->total_quantity - $usedQuantity) . " libres en ese horario."
                        ])->withInput();
                    }
                }
            }
        }

        // 3. Inyectar datos automáticos de la solicitud
        $validatedData['status'] = 'pendiente';
        $validatedData['requester_id'] = auth()->id();

        $event = Event::create($validatedData);

        // Asociar componentes físicos
        if ($request->has('equipment')) {
            foreach ($request->equipment as $equip_id => $quantity_requested) {
                if ($quantity_requested > 0) {
                    $event->equipment()->attach($equip_id, ['quantity_reserved' => $quantity_requested]);
                }
            }
        }

        return redirect()->route('dashboard')->with('status', '¡Tu solicitud ha sido enviada al administrador para su revisión!');
    }
    public function requests()
    {
        // Protegemos la ruta para que solo el Admin pueda entrar
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        // Traemos los eventos pendientes con toda su información relacional
        $pendingEvents = Event::with(['auditorium', 'equipment', 'requester'])
            ->where('status', 'pendiente')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('events.requests', compact('pendingEvents'));
    }

    public function approve(Request $request, Event $event)
    {
        // Validamos que envíen un arreglo con al menos un nombre
        $request->validate([
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'required|string|max:255',
        ]);

        // Unimos todos los nombres del array separados por una coma y un espacio
        $nombresUnidos = implode(', ', $request->instructors);

        // El admin aprueba y guardamos la lista de encargados
        $event->update([
            'status' => 'aprobado',
            'instructor_name' => $nombresUnidos,
        ]);

        return back()->with('status', '¡El evento "' . $event->title . '" ha sido aprobado y publicado con sus encargados!');
    }

    public function reject(Event $event)
    {
        // Marcamos como rechazado
        $event->update(['status' => 'rechazado']);
        
        // Desvinculamos los equipos para que vuelvan al inventario global
        $event->equipment()->detach();

        return back()->with('status', 'Solicitud rechazada. Los recursos han sido liberados.');
    }
}