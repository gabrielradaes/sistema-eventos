<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nuevo Curso
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                <!-- Mostrar errores de validación (Ej. Choque de horarios) -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong>Hubo un problema:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('events.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Título del Curso</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="col-span-2 mt-4">
                            <label class="block text-sm font-medium text-gray-700">Encargado / Instructor del Curso</label>
                            <input type="text" name="instructor_name" value="{{ old('instructor_name') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="col-span-2 mt-4">
                            <label class="block text-sm font-medium text-gray-700">Lista de Participantes / Detalles (Opcional)</label>
                            <textarea name="description" rows="4" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej. Juan Pérez, María López...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Auditorio</label>
                            <select name="auditorium_id" class="mt-1 w-full border-gray-300 rounded-md" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($auditoriums as $auditorium)
                                    <option value="{{ $auditorium->id }}" {{ old('auditorium_id') == $auditorium->id ? 'selected' : '' }}>
                                        {{ $auditorium->name }} ({{ $auditorium->location }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Capacidad (Cupos)</label>
                            <input type="number" name="capacity" min="1" value="{{ old('capacity') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio</label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin</label>
                            <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="mt-1 w-full border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    <div class="col-span-2 mt-6 p-4 border rounded-md bg-gray-50">
                        <h3 class="font-bold text-gray-800 mb-2">Recursos y Equipamiento Necesario</h3>
                        <p class="text-sm text-gray-600 mb-4">El sistema validará si hay stock disponible durante las horas del curso.</p>
                        
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($equipments as $equipment)
                                <div class="flex items-center space-x-2">
                                    <input type="number" name="equipment[{{ $equipment->id }}]" min="0" max="{{ $equipment->total_quantity }}" value="0" class="w-20 border-gray-300 rounded-md shadow-sm text-center">
                                    <span class="text-sm text-gray-700">{{ $equipment->name }} <br><span class="text-xs text-gray-500">(Total empresa: {{ $equipment->total_quantity }})</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center border-t pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">
                            Guardar Curso
                        </button>
                        
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-800 hover:underline">
                            Cancelar y volver
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>