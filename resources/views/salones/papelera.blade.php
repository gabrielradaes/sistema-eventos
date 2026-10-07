<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="min-h-screen bg-white py-6 px-4 sm:px-8">
        <div style="max-width: 1400px; margin: 0 auto;">
            
            <h1 style="font-size: 28px; font-weight: bold; color: #475b75; margin-bottom: 24px;">Resumen</h1>

            <p style="font-size: 14px; color: #4b5563; margin-bottom: 8px;">Este horario se encuentra en esta dirección web:</p>
            <a href="{{ route('salones.usar') }}" style="font-size: 15px; color: #2563eb; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                {{ url('/salones/usar') }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 12px;"></i>
            </a>

            <!-- Filtros Superiores -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <div style="font-size: 14px; color: #4b5563;">
                    <span>salones: todos los recursos</span>
                    <select style="border: 1px solid #d1d5db; border-radius: 4px; padding: 2px 8px; margin-left: 6px; font-size: 13px; color: #374151;">
                        <option>Todos</option>
                    </select>
                </div>
                <i class="fa-solid fa-trash-can" style="font-size: 20px; color: #4b5563;"></i>
            </div>

            <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px;">
                <h2 style="font-size: 22px; font-weight: bold; color: #374151;">reservas borradas</h2>
                <a href="{{ route('salones.supervisar') }}" style="color: #2563eb; text-decoration: underline; font-size: 16px; font-weight: bold;">Atrás</a>
            </div>

            <!-- TABLA DE PAPELERA -->
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 40px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">salones</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Cuándo</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Creado por</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Creado el</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Borrado el</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Descripción</th>
                            <th style="padding: 12px 16px; font-weight: bold; color: #475b75; text-decoration: underline;">Nombre de autoridad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr style="border-bottom: 1px solid #e5e7eb; color: #4b5563; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 12px 16px;">{{ $event->salon }}</td>
                            <td style="padding: 12px 16px; white-space: nowrap;">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('D d-n H:i') }}
                            </td>
                            <td style="padding: 12px 16px;">{{ $event->user_name ?? 'administrador' }}</td>
                            <td style="padding: 12px 16px; white-space: nowrap;">
                                {{ $event->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td style="padding: 12px 16px; white-space: nowrap; color: #ef4444;">
                                {{ $event->deleted_at->format('Y-m-d H:i') }}
                            </td>
                            <td style="padding: 12px 16px; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $event->title }}
                            </td>
                            <td style="padding: 12px 16px;">{{ $event->authority_name }}</td>
                            <td style="padding: 12px 16px; text-align: right; color: #3b82f6; font-size: 15px; white-space: nowrap;">
                                <!-- Botón Ver Detalles (Documento) -->
                                <button onclick="openViewModal({{ json_encode($event) }}, '{{ $event->created_at->format('Y-m-d H:i') }}', '{{ $event->deleted_at->format('Y-m-d H:i') }}', '{{ $event->user_name ?? 'administrador' }}')" title="Ver formulario" style="margin-right: 12px; color: #3b82f6; background: none; border: none; cursor: pointer;">
                                    <i class="fa-regular fa-file-lines"></i>
                                </button>
                                
                                <!-- Botón Restaurar (Lápiz) -->
                                <button onclick="openRestoreModal({{ $event->id }}, '{{ $event->start_time }}', '{{ $event->end_time }}', '{{ $event->title }}', '{{ $event->external_coordinator_name ?? 'N/A' }}', '{{ $event->external_coordinator_phone ?? 'N/A' }}', '{{ $event->authority_name ?? 'N/A' }}', '{{ $event->responsible ?? 'N/A' }}', '{{ $event->salon }}', '{{ $event->created_at->format('Y-m-d H:i') }}', '{{ $event->deleted_at->format('Y-m-d H:i') }}', '{{ $event->user_name ?? 'administrador' }}')" title="Restaurar reserva" style="color: #3b82f6; background: none; border: none; cursor: pointer;">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($events->isEmpty())
                        <tr>
                            <td colspan="7" style="padding: 24px; text-align: center; color: #9ca3af;">La papelera está vacía.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- BARRA DE BÚSQUEDA -->
            <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #4b5563; margin-bottom: 50px;">
                <span>Buscar todas las reservas con</span>
                <input type="text" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 8px; font-size: 14px; width: 180px;">
                <span>en campo</span>
                <select style="border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 8px; font-size: 14px; color: #374151;">
                    <option>Creada por</option>
                    <option>Descripción</option>
                </select>
                <button style="background-color: #2563eb; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">
                    Buscar
                </button>
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
                                <!-- Botón Vaciar Papelera -->
                                <form action="{{ route('salones.vaciar_papelera') }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente TODAS las reservas de la papelera? Esto no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #3b82f6; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">Vaciar papelera</button>
                                </form>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Eliminar permanentemente las reservas borradas</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 16px;">
                                <a href="{{ route('salones.usuarios') }}" style="color: #2563eb; text-decoration: none;">Gestión de usuarios</a>
                            </td>
                            <td style="padding: 12px 16px; color: #4b5563;">Añadir o borrar usuarios y superusuarios</td>
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

            <!-- ENLACE PARA VOLVER AL RESUMEN -->
            <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 24px; margin-top: 40px; padding-bottom: 40px;">
                <a href="{{ route('salones.supervisar') }}" style="color: #2563eb; text-decoration: underline; font-size: 14px;">
                    Resumen
                </a>
            </div>

        </div>
    </div>
    <!-- MODAL "VER FORMULARIO" -->
    <div id="modal-view" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; width: 450px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 15px; color: #4b5563;">Ver formulario</h3>
                <button onclick="document.getElementById('modal-view').style.display='none'" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #475b75; font-weight: bold;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div style="padding: 20px; font-size: 13.5px; color: #374151;">
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Nombre del evento*</strong> <span id="view-title"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Autoridad solicitante*</strong> <span id="view-authority"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Tipo de evento*</strong> <span id="view-type"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Requerimientos*</strong> <span id="view-reqs"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Aforo*</strong> <span id="view-capacity"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Tipo de ingreso*</strong> <span id="view-entry"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Nombre del coordinador externo*</strong> <span id="view-ext-name"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Celular del coordinador externo*</strong> <span id="view-ext-phone"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Requerimiento especial</strong> <span id="view-special"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 12px;">
                    <strong style="color: #1f2937;">Nombre de quien registra*</strong> <span id="view-registered-by"></span>
                </div>
                <div style="display: grid; grid-template-columns: 180px 1fr; margin-bottom: 24px;">
                    <strong style="color: #1f2937;">Nombre coordinador interno</strong> <span id="view-int-coordinator"></span>
                </div>

                <div style="font-size: 13px; color: #9ca3af; margin-bottom: 16px;" id="view-footer-text"></div>
                
                <div style="text-align: center;">
                    <a href="#" style="color: #2563eb; text-decoration: underline; margin-right: 12px;">Editar</a>
                    <a href="#" onclick="document.getElementById('modal-view').style.display='none'" style="color: #2563eb; text-decoration: underline;">Cerrar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL "EDITAR / RESTAURAR RESERVA" -->
    <div id="modal-restore" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; width: 600px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 15px; color: #4b5563;">Editar reserva</h3>
                <button onclick="document.getElementById('modal-restore').style.display='none'" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #475b75; font-weight: bold;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form id="form-restore" method="POST" style="padding: 20px;">
                @csrf
                <div style="display: flex; gap: 10px; margin-bottom: 16px; align-items: center;">
                    <i class="fa-regular fa-clock" style="color: #6b7280; width: 20px; text-align: center;"></i>
                    <input type="text" id="rest-start" readonly style="border: 1px solid #f3f4f6; border-radius: 4px; padding: 6px; font-size: 13px; width: 160px; background: #f9fafb; color: #6b7280;">
                    <span style="font-size: 13px; color: #4b5563;">hasta</span>
                    <input type="text" id="rest-end" readonly style="border: 1px solid #f3f4f6; border-radius: 4px; padding: 6px; font-size: 13px; width: 160px; background: #f9fafb; color: #6b7280;">
                </div>

                <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: flex-start;">
                    <i class="fa-solid fa-align-left" style="color: #6b7280; width: 20px; text-align: center; margin-top: 10px;"></i>
                    <textarea id="rest-title" rows="2" readonly style="flex: 1; border: 1px solid #f3f4f6; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb; color: #6b7280;"></textarea>
                </div>

                <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: center;">
                    <i class="fa-solid fa-user" style="color: #6b7280; width: 20px; text-align: center;"></i>
                    <input type="text" id="rest-ext-name" readonly style="width: 250px; border: 1px solid #f3f4f6; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb; color: #6b7280;">
                </div>

                <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: center;">
                    <i class="fa-solid fa-mobile-screen" style="color: #6b7280; width: 20px; text-align: center;"></i>
                    <input type="text" id="rest-ext-phone" readonly style="width: 250px; border: 1px solid #f3f4f6; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb; color: #6b7280;">
                </div>

                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <label style="width: 160px; font-size: 13px; color: #4b5563;">Nombre de autoridad *</label>
                    <input type="text" id="rest-authority" readonly style="flex: 1; border: 1px solid #f3f4f6; border-radius: 4px; padding: 6px; font-size: 13px; background: #f9fafb; color: #6b7280;">
                </div>

                <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <label style="width: 160px; font-size: 13px; color: #4b5563;">Responsable del evento *</label>
                    <textarea id="rest-responsible" rows="2" readonly style="flex: 1; border: 1px solid #f3f4f6; border-radius: 4px; padding: 6px; font-size: 13px; background: #f9fafb; color: #6b7280;"></textarea>
                </div>

                <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <label style="width: 160px; font-size: 13px; color: #4b5563;">Salones</label>
                    <input type="text" id="rest-salon" readonly style="width: 200px; border: 1px solid #f3f4f6; border-radius: 4px; padding: 6px; font-size: 13px; background: #f9fafb; color: #6b7280;">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <div id="rest-footer-created" style="font-size: 11px; color: #9ca3af;"></div>
                        <div id="rest-footer-deleted" style="font-size: 11px; color: #9ca3af;"></div>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 6px 10px; border-radius: 4px;">
                            <i class="fa-regular fa-file-lines"></i>
                        </button>
                        <button type="button" onclick="document.getElementById('modal-restore').style.display='none'" style="background: white; border: 1px solid #d1d5db; padding: 6px 16px; border-radius: 4px; font-size: 13px; cursor: pointer;">Cerrar</button>
                        
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;">Restaurar reserva</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT DE JAVASCRIPT PARA LLENAR LOS DATOS DE LOS MODALES -->
    <script>
        function openViewModal(event, createdStr, deletedStr, username) {
            document.getElementById('view-title').innerText = event.title || 'N/A';
            document.getElementById('view-authority').innerText = event.authority_name || 'N/A';
            document.getElementById('view-type').innerText = event.event_type || 'N/A';
            
            // Requerimientos (convertir array json a string separado por comas)
            let reqs = 'Ninguno';
            if(event.requirements) {
                try {
                    let reqArray = typeof event.requirements === 'string' ? JSON.parse(event.requirements) : event.requirements;
                    if(Array.isArray(reqArray) && reqArray.length > 0) reqs = reqArray.join(' y ');
                } catch(e) {}
            }
            document.getElementById('view-reqs').innerText = reqs;
            
            document.getElementById('view-capacity').innerText = event.capacity || 'N/A';
            document.getElementById('view-entry').innerText = event.entry_type || 'N/A';
            document.getElementById('view-ext-name').innerText = event.external_coordinator_name || 'N/A';
            document.getElementById('view-ext-phone').innerText = event.external_coordinator_phone || 'N/A';
            document.getElementById('view-special').innerText = event.special_requirements || 'N/A';
            document.getElementById('view-registered-by').innerText = event.registered_by || 'N/A';
            document.getElementById('view-int-coordinator').innerText = event.internal_coordinator || 'N/A';
            
            document.getElementById('view-footer-text').innerText = 'Borrado el ' + deletedStr + ' por ' + username;
            
            document.getElementById('modal-view').style.display = 'flex';
        }

        function openRestoreModal(id, start, end, title, extName, extPhone, authority, responsible, salon, created, deleted, user) {
            document.getElementById('rest-start').value = start.slice(0, 16).replace('T', ' ');
            document.getElementById('rest-end').value = end.slice(0, 16).replace('T', ' ');
            document.getElementById('rest-title').value = title;
            document.getElementById('rest-ext-name').value = extName;
            document.getElementById('rest-ext-phone').value = extPhone;
            document.getElementById('rest-authority').value = authority;
            document.getElementById('rest-responsible').value = responsible;
            document.getElementById('rest-salon').value = salon;

            document.getElementById('rest-footer-created').innerText = 'Creado el ' + created + ' por ' + user;
            document.getElementById('rest-footer-deleted').innerText = 'Borrado el ' + deleted + ' por ' + user;

            // Configurar la ruta para que haga POST hacia la URL de restauración
            document.getElementById('form-restore').action = '/salones/papelera/' + id + '/restaurar';
            
            document.getElementById('modal-restore').style.display = 'flex';
        }
    </script>
</x-app-layout>