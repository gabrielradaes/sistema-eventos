<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Importar FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/es.global.min.js'></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- ALERTAS GLOBALES -->
                @if(session('status'))
                    <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded shadow-sm border border-green-400">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded shadow-sm border border-red-400">
                        <strong>Se encontraron problemas:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- VISTA PARA EL ADMINISTRADOR -->
                @if(auth()->user()->role === 'admin')
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold">Gestión de Eventos y Cursos</h3>
                            <p class="text-sm text-gray-600">Panel de control de la organizadora.</p>
                        </div>
                        <!-- Este botón ahora podría ser "Revisar Solicitudes" en la Fase 4 -->
                        <a href="{{ route('events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                            + Crear Nuevo Curso Directamente
                        </a>
                    </div>

                    <!-- CONTENEDOR DEL CALENDARIO DEL ADMIN -->
                    <div class="mb-8 bg-white p-4 shadow-sm border border-gray-200 rounded">
                        <div id='calendar'></div>
                    </div>

                <!-- VISTA PARA EL FUNCIONARIO (USUARIO FINAL) -->
                @else
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-xl font-bold text-gray-800">Solicitar Organización de Evento</h3>
                        <p class="text-sm text-gray-600">Revisa la disponibilidad en el calendario y llena el formulario de petición.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Columna Izquierda: Calendario -->
                        <div class="bg-white shadow-sm border border-gray-200 rounded">
                            <div class="bg-gray-50 px-4 py-2 border-b"><h4 class="font-bold text-gray-700">Disponibilidad en Tiempo Real</h4></div>
                            <div class="p-4">
                                <div id='calendar'></div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Formulario -->
                        <div class="bg-white shadow-sm border border-gray-200 rounded">
                            <div class="bg-gray-50 px-4 py-2 border-b"><h4 class="font-bold text-gray-700">Formulario de Solicitud</h4></div>
                            <div class="p-4">
                                <form action="{{ route('events.request') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nombre del Evento</label>
                                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Área / Piso</label>
                                                <select name="auditorium_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                                                    <option value="">-- Ubicación --</option>
                                                    @foreach($auditoriums as $auditorium)
                                                        <option value="{{ $auditorium->id }}" {{ old('auditorium_id') == $auditorium->id ? 'selected' : '' }}>
                                                            {{ $auditorium->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Capacidad Necesaria</label>
                                                <input type="number" name="capacity" min="1" value="{{ old('capacity') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha y Hora Inicio</label>
                                                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="mt-1 w-full border-gray-300 rounded-md text-sm" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fecha y Hora Fin</label>
                                                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="mt-1 w-full border-gray-300 rounded-md text-sm" required>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Encargado / Instructor</label>
                                                <input type="text" name="instructor_name" value="{{ old('instructor_name') }}" class="mt-1 w-full border-gray-300 rounded-md text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Personal de Apoyo</label>
                                                <input type="text" name="support_staff" value="{{ old('support_staff') }}" class="mt-1 w-full border-gray-300 rounded-md text-sm" placeholder="Ej. Logística, Seguridad">
                                            </div>
                                        </div>

                                        <div class="pt-4 border-t">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Equipamiento Solicitado</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($equipments as $equipment)
                                                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded border">
                                                        <span class="text-xs text-gray-700 font-medium">{{ $equipment->name }}</span>
                                                        <input type="number" name="equipment[{{ $equipment->id }}]" min="0" max="{{ $equipment->total_quantity }}" value="0" class="w-16 border-gray-300 rounded-md py-1 text-center text-sm">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="pt-4">
                                            <button type="submit" class="w-full bg-blue-600 text-white font-bold px-4 py-3 rounded shadow hover:bg-blue-700">
                                                Enviar Solicitud a Administración
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- SCRIPT DE FULLCALENDAR COMPARTIDO -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek', // Vista semanal por horas (más útil para evitar choques)
                    locale: 'es',
                    height: 650,
                    slotMinTime: '07:00:00', // El calendario empieza a las 7 AM
                    slotMaxTime: '22:00:00', // Termina a las 10 PM
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    events: [
                        @foreach($events as $event)
                        {
                            title: '{{ $event->title }} ({{ $event->auditorium->name }})',
                            start: '{{ \Carbon\Carbon::parse($event->start_time)->toIso8601String() }}',
                            end: '{{ \Carbon\Carbon::parse($event->end_time)->toIso8601String() }}',
                            // Los eventos pendientes salen en naranja, los aprobados en azul
                            backgroundColor: '{{ $event->status == 'pendiente' ? '#f59e0b' : '#2563eb' }}',
                            borderColor: 'transparent',
                            @if(auth()->user()->role === 'admin')
                                url: '{{ route('events.edit', $event) }}',
                            @endif
                        },
                        @endforeach
                    ]
                });
                calendar.render();
            }
        });
    </script>
</x-app-layout>