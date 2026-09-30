<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bandeja de Solicitudes Pendientes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-6 bg-green-100 text-green-700 px-4 py-3 rounded shadow border border-green-400">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                @forelse($pendingEvents as $event)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                        <div class="p-6 flex flex-col md:flex-row justify-between">
                            
                            <!-- Detalles de la Solicitud -->
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-800">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    Solicitado por: <span class="font-bold">{{ $event->requester->name ?? 'Usuario Desconocido' }}</span>
                                </p>

                                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 mb-4">
                                    <div><strong>Ubicación:</strong> {{ $event->auditorium->name }}</div>
                                    <div><strong>Capacidad:</strong> {{ $event->capacity }} personas</div>
                                    <div><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Fin:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Personal de Apoyo:</strong> {{ $event->support_staff ?: 'Ninguno' }}</div>
                                    <div><strong>Encargado Sugerido:</strong> {{ $event->instructor_name ?: 'Sin asignar' }}</div>
                                </div>

                                <!-- Inventario Solicitado -->
                                @if($event->equipment->count() > 0)
                                    <div class="mt-4 p-3 bg-gray-50 rounded border">
                                        <h4 class="font-bold text-sm mb-2 text-gray-700">Equipos solicitados:</h4>
                                        <ul class="list-disc pl-5 text-sm">
                                            @foreach($event->equipment as $item)
                                                <li>{{ $item->pivot->quantity_reserved }}x {{ $item->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- Acciones del Administrador -->
                            <div class="mt-4 md:mt-0 md:ml-6 md:w-1/3 flex flex-col justify-center space-y-3 bg-gray-50 p-4 rounded border">
                                <h4 class="font-bold text-center text-gray-700">Resolución</h4>
                                
                                <form action="{{ route('events.approve', $event) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Confirmar/Asignar Encargado(s):</label>
                                    
                                    <!-- Contenedor dinámico de inputs -->
                                    <div id="instructors-container-{{ $event->id }}" class="space-y-2 mb-2">
                                        <div class="flex items-center space-x-2">
                                            <!-- Nota que el name ahora es instructores[] (con corchetes) -->
                                            <input type="text" name="instructors[]" value="{{ $event->instructor_name }}" class="w-full text-sm border-gray-300 rounded shadow-sm" required placeholder="Nombre del encargado">
                                        </div>
                                    </div>

                                    <!-- Botón mágico para agregar más campos -->
                                    <button type="button" onclick="addInstructor({{ $event->id }})" class="text-xs text-blue-600 hover:text-blue-800 font-bold mb-4 flex items-center">
                                        + Agregar otro encargado
                                    </button>

                                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-bold shadow hover:bg-green-700 mt-2">
                                        Aprobar y Publicar
                                    </button>
                                </form>

                                <form action="{{ route('events.reject', $event) }}" method="POST" onsubmit="return confirm('¿Estás seguro de rechazar esta solicitud?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded font-bold shadow hover:bg-red-700">
                                        Rechazar Solicitud
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white p-8 text-center text-gray-500 rounded shadow-sm">
                        No hay solicitudes pendientes por revisar.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
    <!-- Script para agregar múltiples encargados dinámicamente -->
    <script>
        function addInstructor(eventId) {
            // Buscamos el contenedor específico de ese evento
            const container = document.getElementById('instructors-container-' + eventId);
            
            // Creamos un nuevo div con el input y un botón para eliminar
            const inputDiv = document.createElement('div');
            inputDiv.className = 'flex items-center space-x-2 mt-2';
            inputDiv.innerHTML = `
                <input type="text" name="instructors[]" class="w-full text-sm border-gray-300 rounded shadow-sm" placeholder="Nombre del encargado" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:bg-red-100 rounded px-2 py-1 font-bold transition" title="Eliminar este encargado">X</button>
            `;
            
            // Lo agregamos al contenedor
            container.appendChild(inputDiv);
        }
    </script>
</x-app-layout>