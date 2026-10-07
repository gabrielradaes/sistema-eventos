<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="min-h-screen bg-white py-6 px-4 sm:px-8">
        <div style="max-width: 1400px; margin: 0 auto;">
            
            <!-- Cabecera y Botones Usar/Supervisar -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <h1 style="font-size: 28px; font-weight: bold; color: #475b75;">Opciones de supervisión para salones</h1>
                
                <div style="display: flex; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <a href="{{ route('salones.usar') }}" style="background-color: white; padding: 6px 16px; color: #3b82f6; border-right: 1px solid #e5e7eb; font-size: 14px; text-decoration: none;">
                        <i class="fa-regular fa-calendar" style="margin-right: 8px;"></i> Usar
                    </a>
                    <button style="background-color: #f9fafb; padding: 6px 16px; font-weight: bold; color: #374151; font-size: 14px; border: none;">
                        <i class="fa-solid fa-sliders" style="margin-right: 8px;"></i> Supervisar
                    </button>
                </div>
            </div>

            <!-- Pestañas (Tabs) -->
            <div style="display: flex; gap: 24px; border-bottom: 2px solid #f3f4f6; margin-bottom: 24px;">
                <div style="padding: 8px 12px; border-bottom: 3px solid #e0e7ff; color: #4f46e5; font-weight: bold; font-size: 14px; cursor: pointer; background-color: #f5f3ff;">RESUMEN</div>
                <div style="padding: 8px 12px; color: #6b7280; font-size: 14px; cursor: pointer;">DESCARGAR</div>
            </div>

            <h2 style="font-size: 22px; font-weight: bold; color: #374151; margin-bottom: 16px;">Resumen</h2>

            <p style="font-size: 14px; color: #4b5563; margin-bottom: 8px;">Este horario se encuentra en esta dirección web:</p>
            <a href="{{ route('salones.usar') }}" style="font-size: 15px; color: #2563eb; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                {{ url('/salones/usar') }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 12px;"></i>
            </a>

            <!-- Filtros y Enlaces -->
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 12px; font-size: 14px;">
                <div>
                    <span style="color: #4b5563;">salones: todos los recursos</span>
                    <select style="border: 1px solid #d1d5db; border-radius: 4px; padding: 2px 8px; margin-left: 6px; font-size: 13px; color: #374151;">
                        <option>Todos</option>
                    </select>
                </div>
                <a href="#" style="color: #3b82f6; text-decoration: underline;">Mostrar historial</a>
                <a href="{{ route('salones.papelera') }}" style="color: #3b82f6; text-decoration: underline;">Mostrar papelera</a>
            </div>

            <!-- TABLA PRINCIPAL DE RESERVAS -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 40px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">salones</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Cuándo</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Creado por</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Creado el</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Descripción</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Nombre de autoridad</th>
                            <th style="padding: 12px 16px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr style="border-bottom: 1px solid #e5e7eb; color: #4b5563; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 12px 16px;">{{ $event->salon }}</td>
                            <td style="padding: 12px 16px; white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('d-m-Y H:i') }}
                            </td>
                            <td style="padding: 12px 16px;">{{ $event->user_name ?? 'N/A' }}</td>
                            <td style="padding: 12px 16px; white-space: nowrap;">
                                {{ $event->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td style="padding: 12px 16px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $event->title }}">
                                {{ $event->title }}
                            </td>
                            <td style="padding: 12px 16px;">{{ $event->authority_name }}</td>
                            <td style="padding: 12px 16px; text-align: right; color: #3b82f6; font-size: 15px; white-space: nowrap;">
                                <a href="{{ route('salones.protocolo', $event->id) }}" title="Documento Protocolo" style="margin-right: 12px; color: #3b82f6;"><i class="fa-regular fa-file-lines"></i></a>
                                <a href="{{ route('salones.usar') }}" title="Ver en Calendario" style="margin-right: 12px; color: #3b82f6;"><i class="fa-regular fa-calendar"></i></a>
                                <a href="#" title="Editar" style="color: #3b82f6;"><i class="fa-regular fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($events->isEmpty())
                        <tr>
                            <td colspan="7" style="padding: 24px; text-align: center; color: #9ca3af;">No hay reservas registradas en el sistema.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- TABLA INFERIOR DE FUNCIONES -->
            <div style="max-width: 550px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Función</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75;">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 16px;">
                                <a href="#" style="background-color: #c7d2fe; color: #4338ca; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 13px;">Bloquear horario</a>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Bloquear horario para usuarios</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 16px;">
                                <a href="{{ route('salones.usuarios') }}" style="color: #2563eb; text-decoration: none;">Gestión de usuarios</a>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Añadir o borrar usuarios y superusuarios</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 16px;">
                                <a href="{{ route('salones.papelera') }}" style="color: #2563eb; text-decoration: none;">Mostrar papelera</a>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Mostrar reservas recién borradas</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 16px;">
                                <a href="#" style="color: #2563eb; text-decoration: none;">Descargar horario</a>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Descargar horario en formato Excel o CSV</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>