<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cursos Disponibles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-xl font-bold mb-2">Cursos Disponibles</h3>
                    <p class="text-sm text-gray-600 mb-6">Selecciona un curso para inscribirte.</p>

                    <!-- Alertas para el Funcionario -->
                    @if(session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->has('enroll_error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-sm">
                            <strong>Error:</strong> {{ $errors->first('enroll_error') }}
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($events as $event)
                            <div class="border rounded-lg p-4 shadow-sm bg-white hover:shadow-md transition flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-lg text-blue-800">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-2"><strong>Lugar:</strong> {{ $event->auditorium->name }} ({{ $event->auditorium->location }})</p>
                                    <p class="text-sm text-gray-600"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-gray-600"><strong>Fin:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</p>
                                    
                                    <!-- Mostramos los cupos dinámicamente -->
                                    <p class="text-sm mt-2 {{ $event->users->count() >= $event->capacity ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                        <strong>Cupos:</strong> {{ $event->capacity - $event->users->count() }} disponibles
                                    </p>
                                </div>
                                
                                <!-- Formulario conectado a la ruta de inscripción -->
                                <form action="{{ route('events.enroll') }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 disabled:opacity-50" 
                                        {{ $event->users->count() >= $event->capacity ? 'disabled' : '' }}>
                                        Inscribirme
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="col-span-full p-4 bg-gray-50 text-center text-gray-500 rounded border">
                                No hay cursos disponibles en este momento.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>