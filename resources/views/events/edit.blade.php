<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Curso: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.update', $event) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Auditorio</label>
                            <select name="auditorium_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                                @foreach($auditoriums as $auditorium)
                                    <option value="{{ $auditorium->id }}" {{ (old('auditorium_id', $event->auditorium_id) == $auditorium->id) ? 'selected' : '' }}>
                                        {{ $auditorium->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capacidad</label>
                            <input type="number" name="capacity" min="1" value="{{ old('capacity', $event->capacity) }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio</label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin</label>
                            <input type="datetime-local" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Actualizar</button>
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline mt-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>