<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="min-h-screen bg-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm p-8">
            
            <h2 class="text-2xl font-bold text-[#475b75] text-center mb-8">Reservas de salón</h2>

            <form action="{{ route('salones.protocolo.update', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nombre del evento -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del evento *</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Autoridad solicitante -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Autoridad solicitante *</label>
                    <input type="text" name="authority_name" value="{{ old('authority_name', $event->authority_name) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Agregar si es diputado (Dip) o senador (Sen)</p>
                </div>

                <!-- Tipo de evento -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de evento *</label>
                    <select name="event_type" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-white">
                        <option value="Reconocimientos" {{ $event->event_type == 'Reconocimientos' ? 'selected' : '' }}>Reconocimientos</option>
                        <option value="Reunión" {{ $event->event_type == 'Reunión' ? 'selected' : '' }}>Reunión</option>
                        <option value="Conferencia" {{ $event->event_type == 'Conferencia' ? 'selected' : '' }}>Conferencia</option>
                        <option value="Taller / Seminario" {{ $event->event_type == 'Taller / Seminario' ? 'selected' : '' }}>Taller / Seminario</option>
                    </select>
                </div>

                <!-- Requerimientos (Checkboxes) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Requerimientos *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
                        @php 
                            $reqs = $event->requirements ?? []; 
                        @endphp
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Sonido" {{ in_array('Sonido', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Sonido</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Maestro de ceremonias" {{ in_array('Maestro de ceremonias', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Maestro de ceremonias</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Tiene refrigerio" {{ in_array('Tiene refrigerio', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Tiene refrigerio</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Prensa" {{ in_array('Prensa', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Prensa</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Testera" {{ in_array('Testera', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Testera</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Atril" {{ in_array('Atril', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Atril</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Protocolo sala" {{ in_array('Protocolo sala', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Protocolo sala</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Protocolo antesala" {{ in_array('Protocolo antesala', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Protocolo antesala</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Guía" {{ in_array('Guía', $reqs) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Guía</span></label>
                    </div>
                    <p class="text-xs text-blue-500 mt-2 cursor-pointer">Recomiende según el tipo de evento</p>
                </div>

                <!-- Aforo -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Aforo *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" placeholder="Cantidad de personas en el evento" class="w-full border border-gray-300 rounded-md p-2.5 text-sm" required>
                </div>

                <!-- Tipo de ingreso -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de ingreso *</label>
                    <select name="entry_type" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-white">
                        <option value="Libre" {{ $event->entry_type == 'Libre' ? 'selected' : '' }}>Libre</option>
                        <option value="Con invitación" {{ $event->entry_type == 'Con invitación' ? 'selected' : '' }}>Con invitación</option>
                        <option value="Lista cerrada" {{ $event->entry_type == 'Lista cerrada' ? 'selected' : '' }}>Lista cerrada</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Como ingresaran al edificio</p>
                </div>

                <!-- Nombre del coordinador externo -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del coordinador externo *</label>
                    <input type="text" name="external_coordinator_name" value="{{ old('external_coordinator_name', $event->external_coordinator_name) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Nombre completo</p>
                </div>

                <!-- Celular del coordinador externo -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Celular del coordinador externo *</label>
                    <input type="text" name="external_coordinator_phone" value="{{ old('external_coordinator_phone', $event->external_coordinator_phone) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm">
                </div>

                <!-- Requerimiento especial -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Requerimiento especial</label>
                    <input type="text" name="special_requirements" value="{{ old('special_requirements', $event->special_requirements) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Personal o característica</p>
                </div>

                <!-- Nombre de quien registra -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nombre de quien registra *</label>
                    <input type="text" name="registered_by" value="{{ old('registered_by', $event->registered_by ?? Auth::user()->name) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-gray-50">
                </div>

                <!-- Nombre coordinador interno -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nombre coordinador interno</label>
                    <input type="text" name="internal_coordinator" value="{{ old('internal_coordinator', $event->internal_coordinator) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm">
                </div>

                <!-- Botones de Acción Finales -->
                <div class="flex items-center justify-center space-x-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-[#2563eb] text-white px-6 py-2.5 rounded-md text-sm font-bold hover:bg-blue-700 transition">
                        Completado
                    </button>
                    <a href="{{ route('salones.usar') }}" class="text-blue-600 text-sm hover:underline">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>