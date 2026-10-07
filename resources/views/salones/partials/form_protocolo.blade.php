@php
    // Si la variable $readonly viene en true, los campos estarán bloqueados
    $isReadonly = isset($readonly) && $readonly === true;
    $reqs = old('requirements', isset($event) ? ($event->requirements ?? []) : []);
    if(is_string($reqs)) { $reqs = json_decode($reqs, true) ?? []; }
@endphp

<div class="space-y-6">
    <!-- Nombre del evento -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del evento *</label>
        <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : 'focus:ring-blue-500 focus:border-blue-500' }}" {{ $isReadonly ? 'readonly' : 'required' }}>
    </div>

    <!-- Autoridad solicitante -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Autoridad solicitante *</label>
        <input type="text" name="authority_name" value="{{ old('authority_name', $event->authority_name ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
        <p class="text-xs text-gray-400 mt-1">Agregar si es diputado (Dip) o senador (Sen)</p>
    </div>

    <!-- Tipo de evento -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de evento *</label>
        @if($isReadonly)
            <input type="text" value="{{ $event->event_type ?? '' }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-gray-100" readonly>
        @else
            <select name="event_type" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-white">
                <option value="Reconocimientos" {{ (old('event_type', $event->event_type ?? '') == 'Reconocimientos') ? 'selected' : '' }}>Reconocimientos</option>
                <option value="Reunión" {{ (old('event_type', $event->event_type ?? '') == 'Reunión') ? 'selected' : '' }}>Reunión</option>
                <option value="Conferencia" {{ (old('event_type', $event->event_type ?? '') == 'Conferencia') ? 'selected' : '' }}>Conferencia</option>
                <option value="Taller / Seminario" {{ (old('event_type', $event->event_type ?? '') == 'Taller / Seminario') ? 'selected' : '' }}>Taller / Seminario</option>
            </select>
        @endif
    </div>

    <!-- Requerimientos (Checkboxes) -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-2">Requerimientos *</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Sonido" {{ in_array('Sonido', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Sonido</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Maestro de ceremonias" {{ in_array('Maestro de ceremonias', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Maestro de ceremonias</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Tiene refrigerio" {{ in_array('Tiene refrigerio', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Tiene refrigerio</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Prensa" {{ in_array('Prensa', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Prensa</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Testera" {{ in_array('Testera', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Testera</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Atril" {{ in_array('Atril', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Atril</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Protocolo sala" {{ in_array('Protocolo sala', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Protocolo sala</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Protocolo antesala" {{ in_array('Protocolo antesala', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Protocolo antesala</span></label>
            <label class="flex items-center space-x-2"><input type="checkbox" name="requirements[]" value="Guía" {{ in_array('Guía', $reqs) ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }} class="rounded border-gray-300 text-blue-600"> <span>Guía</span></label>
        </div>
    </div>

    <!-- Aforo -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Aforo *</label>
        <input type="number" name="capacity" value="{{ old('capacity', $event->capacity ?? '') }}" placeholder="Cantidad de personas" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : 'required' }}>
    </div>

    <!-- Tipo de ingreso -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de ingreso *</label>
        @if($isReadonly)
            <input type="text" value="{{ $event->entry_type ?? '' }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-gray-100" readonly>
        @else
            <select name="entry_type" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-white">
                <option value="Libre" {{ (old('entry_type', $event->entry_type ?? '') == 'Libre') ? 'selected' : '' }}>Libre</option>
                <option value="Con invitación" {{ (old('entry_type', $event->entry_type ?? '') == 'Con invitación') ? 'selected' : '' }}>Con invitación</option>
                <option value="Lista cerrada" {{ (old('entry_type', $event->entry_type ?? '') == 'Lista cerrada') ? 'selected' : '' }}>Lista cerrada</option>
            </select>
        @endif
        <p class="text-xs text-gray-400 mt-1">Como ingresarán al edificio</p>
    </div>

    <!-- Nombre del coordinador externo -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre del coordinador externo *</label>
        <input type="text" name="external_coordinator_name" value="{{ old('external_coordinator_name', $event->external_coordinator_name ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
    </div>

    <!-- Celular del coordinador externo -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Celular del coordinador externo *</label>
        <input type="text" name="external_coordinator_phone" value="{{ old('external_coordinator_phone', $event->external_coordinator_phone ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
    </div>

    <!-- Requerimiento especial -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Requerimiento especial</label>
        <input type="text" name="special_requirements" value="{{ old('special_requirements', $event->special_requirements ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
    </div>

    <!-- Nombre de quien registra -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre de quien registra *</label>
        <input type="text" name="registered_by" value="{{ old('registered_by', $event->registered_by ?? (Auth::user()->name ?? '')) }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm bg-gray-50" {{ $isReadonly ? 'readonly' : '' }}>
    </div>

    <!-- Nombre coordinador interno -->
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre coordinador interno</label>
        <input type="text" name="internal_coordinator" value="{{ old('internal_coordinator', $event->internal_coordinator ?? '') }}" class="w-full border border-gray-300 rounded-md p-2.5 text-sm {{ $isReadonly ? 'bg-gray-100' : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
    </div>
</div>