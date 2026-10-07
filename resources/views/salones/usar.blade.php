<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/es.global.min.js'></script>

    <style>
        .fc .fc-toolbar { display: none !important; }
        .fc-theme-standard th, .fc-theme-standard td { border-color: #e2e8f0; }
        .fc-theme-standard th { border-top: none !important; padding-bottom: 8px; border-left: none !important; border-right: none !important; }
        .fc-scrollgrid { border: none !important; }
        
        .fc .fc-day-today { background-color: #fafafa !important; }
        
        .fc-timegrid-axis-cushion { display: none !important; }
        .fc-timegrid-slot-label-cushion { font-size: 0.8rem; color: #475b75; }

        /* Estilos del Mini Calendario */
        .mini-cal { width: 100%; text-align: center; font-size: 13px; border-collapse: separate; border-spacing: 2px; }
        .mini-cal th { color: #9ca3af; font-weight: normal; font-size: 12px; padding-bottom: 4px; }
        .mini-cal td { padding: 5px 0; border-radius: 4px; cursor: pointer; color: #4b5563; font-weight: 500; transition: background 0.2s; background-color: #ffffff; }
        
        .mc-has-event { background-color: #f59e0b !important; color: white !important; font-weight: bold; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .mc-hover:hover { background-color: #f3f4f6 !important; }
        
        /* Contorno azul para el día actual real (nunca cambia) */
        .mc-today-real {
            border: 2px solid #2563eb !important;
            color: #2563eb !important;
            font-weight: bold;
        }

        /* Fila completa en tono plomo cuando la semana está seleccionada */
        .mc-row-selected td {
            background-color: #e2e8f0 !important;
        }
        /* Mantener color en el día de hoy o eventos aunque la fila esté seleccionada */
        .mc-row-selected td.mc-today-real {
            background-color: #ffffff !important;
            border: 2px solid #2563eb !important;
        }
        .mc-row-selected td.mc-has-event {
            background-color: #f59e0b !important;
        }
    </style>

    <div class="min-h-screen bg-white py-6 px-4 sm:px-8">
        <div style="max-width: 1400px; margin: 0 auto;">
            
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                <div>
                    <h1 style="font-size: 28px; font-weight: bold; color: #475b75; margin-bottom: 4px;">Horario para salones</h1>
                    <p style="font-size: 14px; color: #9ca3af;">Haga clic en un espacio libre para crear una nueva reserva. Haga clic en cualquier reserva para editarla.</p>
                </div>
                
                <div style="display: flex; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <button style="background-color: #f9fafb; padding: 6px 16px; font-weight: bold; color: #374151; border-right: 1px solid #e5e7eb; font-size: 14px;">
                        <i class="fa-regular fa-calendar text-blue-500" style="margin-right: 8px;"></i> Usar
                    </button>
                    <a href="{{ route('salones.supervisar') }}" style="background-color: white; padding: 6px 16px; color: #3b82f6; font-size: 14px; text-decoration: none;">
                        <i class="fa-solid fa-sliders" style="margin-right: 8px;"></i> Supervisar
                    </a>
                </div>
            </div>

            <div style="display: flex; gap: 30px;">
                
                <!-- CALENDARIO GRANDE -->
                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        
                        <div style="position: relative;" id="view-dropdown-container">
                            <button id="btn-view-dropdown" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 5px 12px; font-size: 14px; background: white; color: #374151; cursor: pointer; display: flex; align-items: center;">
                                <span id="current-view-text">Semana</span> 
                                <i class="fa-solid fa-chevron-down" style="color: #3b82f6; font-size: 12px; margin-left: 10px;"></i>
                            </button>
                            
                            <div id="view-menu" style="display: none; position: absolute; left: 0; top: 100%; margin-top: 4px; width: 140px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); z-index: 50; padding: 6px 0; font-size: 14px; color: #4b5563;">
                                <a href="#" class="view-option" data-view="dayGridMonth" style="display: block; padding: 6px 16px; text-decoration: none; color: inherit;">Mes</a>
                                <a href="#" class="view-option" data-view="timeGridWeek" style="display: block; padding: 6px 16px; text-decoration: none; color: #1f2937; font-weight: bold; background-color: #f9fafb;">Semana</a>
                                <a href="#" class="view-option" data-view="timeGridDay" style="display: block; padding: 6px 16px; text-decoration: none; color: inherit;">Día</a>
                                <a href="#" class="view-option" data-view="listWeek" style="display: block; padding: 6px 16px; text-decoration: none; color: inherit;">Agenda</a>
                                <a href="#" class="view-option" data-view="listYear" style="display: block; padding: 6px 16px; text-decoration: none; color: inherit;">Disponible</a>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 24px;">
                            <button id="btn-hoy" style="background: #94a3b8; color: white; padding: 4px 12px; border-radius: 4px; font-size: 14px; border: none; cursor: pointer;">
                                Hoy
                            </button>
                            <div style="display: flex; align-items: center; font-size: 18px; color: #3b82f6; font-weight: bold;">
                                <i class="fa-solid fa-chevron-left" id="btn-prev" style="cursor: pointer; padding: 0 8px;"></i>
                                <span id="calendar-title" style="color: #475b75; font-size: 15px; margin: 0 16px; font-weight: normal; text-transform: capitalize;">Semana 42</span>
                                <i class="fa-solid fa-chevron-right" id="btn-next" style="cursor: pointer; padding: 0 8px;"></i>
                            </div>
                        </div>
                    </div>

                    <div id="calendar" style="background: white;"></div>
                </div>

                <!-- MINI CALENDARIO DINÁMICO -->
                <div style="width: 260px; flex-shrink: 0; padding-top: 50px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; color: #3b82f6; font-size: 14px;">
                        <i class="fa-solid fa-chevron-left" id="mini-prev" style="cursor: pointer;"></i>
                        <span id="mini-cal-month-title" style="color: #475b75; font-weight: bold; font-size: 13px; text-transform: capitalize;">octubre 2026</span>
                        <i class="fa-solid fa-chevron-right" id="mini-next" style="cursor: pointer;"></i>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <table class="mini-cal" id="mini-calendar-table">
                            <thead>
                                <tr>
                                    <th>DO</th><th>LU</th><th>MA</th><th>MI</th><th>JU</th><th>VI</th><th>SA</th>
                                </tr>
                            </thead>
                            <tbody id="mini-cal-body">
                                <!-- Generado dinámicamente -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Leyendas de Salones -->
                    <div style="display: flex; flex-wrap: wrap; gap: 6px; font-size: 11px; font-weight: bold; color: white;">
                        <span style="background-color: #9ca3af; padding: 4px 8px; border-radius: 4px; cursor: pointer;"><i class="fa-solid fa-check" style="margin-right: 4px;"></i> Todos</span>
                        <span style="background-color: #eab308; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Interpelaciones -1</span>
                        <span style="background-color: #a855f7; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Multipropósito - PB</span>
                        <span style="background-color: #14b8a6; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Salón de Honor - P16</span>
                        <span style="background-color: #78716c; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Patio Histórico - PB</span>
                        <span style="background-color: #22c55e; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Salón Marcelo Quiroga - PL</span>
                        <span style="background-color: #ec4899; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Salón Andrés Ibáñez - PL</span>
                        <span style="background-color: #f97316; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Comedor de Diputados - P10</span>
                        <span style="background-color: #10b981; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Hemiciclo</span>
                        <span style="background-color: #06b6d4; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Visita Guiada</span>
                        <span style="background-color: #0ea5e9; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Hall ALP</span>
                        <span style="background-color: #84cc16; padding: 4px 8px; border-radius: 4px; cursor: pointer;">SISTEMAS</span>
                    </div>

                </div>
            </div>
        </div>
        <!-- MODAL FLOTANTE "NUEVA RESERVA" -->
        <div id="booking-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
            <div style="background: white; width: 600px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
                
                <!-- Cabecera del Modal -->
                <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #475b75;">Nueva reserva</h3>
                    <button id="close-modal" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #9ca3af;"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <!-- Cuerpo del Formulario -->
                <form id="booking-form" style="padding: 20px;">
                    @csrf
                    <div style="display: flex; gap: 10px; margin-bottom: 16px; align-items: center;">
                        <i class="fa-regular fa-clock" style="color: #6b7280;"></i>
                        <input type="text" id="modal-start" name="start_time" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; width: 180px;" required>
                        <span>hasta</span>
                        <input type="text" id="modal-end" name="end_time" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; width: 180px;" required>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <textarea id="modal-title" name="title" placeholder="Descripción *" rows="2" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required></textarea>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <input type="text" id="modal-username" name="user_name" value="{{ Auth::user()->name }}" placeholder="Nombre de usuario" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <input type="text" id="modal-phone" name="user_phone" placeholder="Teléfono" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;">
                    </div>

                    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                    <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Nombre de autoridad *</label>
                        <input type="text" name="authority_name" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px;">
                    </div>

                    <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Responsable del evento *</label>
                        <textarea name="responsible" rows="2" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px;"></textarea>
                    </div>

                    <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Salones</label>
                        <select name="salon" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px;">
                            <option value="Salón Andrés Ibáñez - PL">Salón Andrés Ibáñez - PL</option>
                            <option value="Salón Marcelo Quiroga - PL">Salón Marcelo Quiroga - PL</option>
                            <option value="Salón de Honor - P16">Salón de Honor - P16</option>
                            <option value="Multipropósito - PB">Multipropósito - PB</option>
                            <option value="Patio Histórico - PB">Patio Histórico - PB</option>
                            <option value="Hemiciclo">Hemiciclo</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" id="btn-cancel" style="background: white; border: 1px solid #d1d5db; padding: 6px 16px; border-radius: 4px; font-size: 13px; cursor: pointer;">Cancelar</button>
                        <button type="submit" style="background: #2563eb; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;">Crear reserva</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- MODAL FLOTANTE "EDITAR RESERVA" -->
        <div id="edit-booking-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
            <div style="background: white; width: 600px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
                
                <!-- Cabecera del Modal -->
                <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #475b75;">Editar reserva</h3>
                    <button id="close-edit-modal" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #9ca3af;"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <!-- Cuerpo del Formulario -->
                <form id="edit-booking-form" style="padding: 20px;">
                    @csrf
                    <input type="hidden" id="edit-event-id" name="id">
                    
                    <div style="display: flex; gap: 10px; margin-bottom: 16px; align-items: center;">
                        <i class="fa-regular fa-clock" style="color: #6b7280; width: 20px; text-align: center;"></i>
                        <input type="text" id="edit-start" name="start_time" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; width: 160px;" required>
                        <span style="font-size: 13px; color: #4b5563;">hasta</span>
                        <input type="text" id="edit-end" name="end_time" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; width: 160px;" required>
                    </div>

                    <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: flex-start;">
                        <i class="fa-solid fa-align-left" style="color: #6b7280; width: 20px; text-align: center; margin-top: 10px;"></i>
                        <textarea id="edit-title" name="title" rows="2" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb;" required></textarea>
                    </div>

                    <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: center;">
                        <i class="fa-solid fa-user" style="color: #6b7280; width: 20px; text-align: center;"></i>
                        <input type="text" id="edit-username" name="user_name" style="width: 250px; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb;">
                    </div>

                    <div style="margin-bottom: 16px; display: flex; gap: 10px; align-items: center;">
                        <i class="fa-solid fa-mobile-screen" style="color: #6b7280; width: 20px; text-align: center;"></i>
                        <input type="text" id="edit-phone" name="user_phone" style="width: 250px; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px; background: #f9fafb;">
                    </div>

                    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                    <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Nombre de autoridad *</label>
                        <input type="text" id="edit-authority" name="authority_name" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; background: #f9fafb;">
                    </div>

                    <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Responsable del evento *</label>
                        <textarea id="edit-responsible" name="responsible" rows="2" style="flex: 1; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px; background: #f9fafb;"></textarea>
                    </div>

                    <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <label style="width: 160px; font-size: 13px; color: #4b5563;">Salones</label>
                        <select id="edit-salon" name="salon" style="width: 200px; border: 1px solid #d1d5db; border-radius: 4px; padding: 6px; font-size: 13px;">
                            <option value="Salón Andrés Ibáñez - PL">Salón Andrés Ibáñez - PL</option>
                            <option value="Salón Marcelo Quiroga - PL">Salón Marcelo Quiroga - PL</option>
                            <option value="Salón de Honor - P16">Salón de Honor - P16</option>
                            <option value="Multipropósito - PB">Multipropósito - PB</option>
                            <option value="Patio Histórico - PB">Patio Histórico - PB</option>
                            <option value="Hemiciclo">Hemiciclo</option>
                            <option value="SISTEMAS">SISTEMAS</option>
                        </select>
                    </div>

                    <!-- Footer y Botones de Acción -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                        <div>
                            <span id="edit-footer-text" style="font-size: 11px; color: #9ca3af;"></span>
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            
                            <button type="button" id="btn-edit-cancel" style="background: white; border: 1px solid #d1d5db; padding: 6px 16px; border-radius: 4px; font-size: 13px; cursor: pointer;">Cerrar</button>
                            
                            {{-- Si el usuario ES ADMIN o SUPERADMIN, mostramos Editar y Protocolo --}}
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                                <button type="button" id="btn-edit-protocol" style="background: white; color: #3b82f6; border: 1px solid #3b82f6; padding: 6px 10px; border-radius: 4px; cursor: pointer;" title="Formulario de protocolo">
                                    <i class="fa-solid fa-file-lines"></i>
                                </button>
                                
                                <button type="submit" id="btn-edit-update" style="background: #2563eb; color: white; border: none; padding: 6px 16px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;">Actualizar reserva</button>
                            @endif

                            {{-- Si el usuario ES ÚNICAMENTE ADMIN, mostramos el basurero --}}
                            @if(auth()->user()->role === 'admin')
                                <button type="button" id="btn-edit-delete" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;" title="Eliminar reserva">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @endif

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            function getWeekNumber(d) {
                d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
                d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
                var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
                return Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                firstDay: 0,
                height: 800, 
                allDaySlot: false, 
                slotMinTime: '08:00:00',
                slotMaxTime: '18:00:00', 
                slotLabelFormat: { hour: 'numeric', minute: '2-digit', omitZeroMinute: false, meridiem: false }, 
                expandRows: true,
                selectable: true, // Permitir seleccionar celdas
                events: '/api/salones/events', // Cargar eventos creados

                // Al hacer clic o seleccionar un bloque de tiempo
                select: function(info) {
                    // Verificamos si el usuario es solo lectura usando Blade
                    let userRole = "{{ auth()->user()->role ?? 'usuario' }}";
                    if(userRole === 'usuario') {
                        alert('Modo de solo lectura: No tienes permisos para crear reservas.');
                        return; // Corta la ejecución aquí, el modal no se abre
                    }

                    var modal = document.getElementById('booking-modal');
                    document.getElementById('modal-start').value = info.startStr.slice(0, 19).replace('T', ' ');
                    document.getElementById('modal-end').value = info.endStr.slice(0, 19).replace('T', ' ');
                    modal.style.display = 'flex';
                },

                // Al hacer clic en un evento YA CREADO
                eventClick: function(info) {
                    var modal = document.getElementById('edit-booking-modal');
                    var e = info.event;
                    var props = e.extendedProps; // Aquí vienen los datos extra desde la base de datos

                    // Llenar el formulario con los datos del evento
                    document.getElementById('edit-event-id').value = e.id;
                    document.getElementById('edit-start').value = e.startStr.slice(0, 16).replace('T', ' ');
                    document.getElementById('edit-end').value = e.endStr ? e.endStr.slice(0, 16).replace('T', ' ') : '';
                    document.getElementById('edit-title').value = e.title;
                    
                    document.getElementById('edit-username').value = props.user_name || '';
                    document.getElementById('edit-phone').value = props.user_phone || '';
                    document.getElementById('edit-authority').value = props.authority_name || '';
                    document.getElementById('edit-responsible').value = props.responsible || '';
                    document.getElementById('edit-salon').value = props.salon || '';

                    // Texto chiquito del footer
                    var fecha = props.created_at ? props.created_at.slice(0, 10) : '';
                    document.getElementById('edit-footer-text').innerText = 'Creado el ' + fecha + ' por ' + (props.user_name || 'usuario');

                    // Cambiar el enlace del botón de protocolo (asumiendo que tu ruta es /salones/protocolo/{id})
                    document.getElementById('btn-edit-protocol').onclick = function() {
                        window.location.href = '/salones/protocolo/' + e.id;
                    };

                    modal.style.display = 'flex';
                },

                dayHeaderContent: function(arg) {
                    var dayNames = ['DO', 'LU', 'MA', 'MI', 'JU', 'VI', 'SA'];
                    var dayStr = dayNames[arg.date.getDay()];
                    var dateNum = arg.date.getDate();
                    return { 
                        html: '<div style="text-align: center; text-transform: uppercase; font-size: 0.8rem; color: #64748b; font-weight: 400;">' + dayStr + '</div>' +
                              '<div style="text-align: center; font-size: 1.6rem; color: #475b75; font-weight: 400; margin-top: 2px;">' + dateNum + '</div>' 
                    };
                },
                datesSet: function(info) {
                    var titleEl = document.getElementById('calendar-title');
                    if (info.view.type === 'timeGridWeek' || info.view.type === 'listWeek') {
                        var weekNumber = getWeekNumber(info.view.currentStart);
                        titleEl.innerText = 'Semana ' + weekNumber;
                    } else {
                        titleEl.innerText = info.view.title;
                    }
                }
            });
            
            calendar.render();

            // Controladores del Modal
            document.getElementById('close-modal').addEventListener('click', () => { document.getElementById('booking-modal').style.display = 'none'; });
            document.getElementById('btn-cancel').addEventListener('click', () => { document.getElementById('booking-modal').style.display = 'none'; });


            // Cerrar el modal de edición
            document.getElementById('close-edit-modal').addEventListener('click', () => { document.getElementById('edit-booking-modal').style.display = 'none'; });
            document.getElementById('btn-edit-cancel').addEventListener('click', () => { document.getElementById('edit-booking-modal').style.display = 'none'; });

            // Enviar formulario de ACTUALIZAR por AJAX
            document.getElementById('edit-booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var eventId = document.getElementById('edit-event-id').value;
                var formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel necesita esto para saber que es un UPDATE

                fetch('/api/salones/events/' + eventId, {
                    method: 'POST', // Mandamos POST pero con _method PUT adentro
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('edit-booking-modal').style.display = 'none';
                        calendar.refetchEvents(); // Recarga los cuadritos mágicamente
                    }
                });
            });

            // Botón ELIMINAR por AJAX
            document.getElementById('btn-edit-delete').addEventListener('click', function() {
                if(confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                    var eventId = document.getElementById('edit-event-id').value;
                    
                    fetch('/api/salones/events/' + eventId, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            document.getElementById('edit-booking-modal').style.display = 'none';
                            calendar.refetchEvents();
                        }
                    });
                }
            });

            // Enviar formulario por AJAX
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                fetch('/api/salones/events', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.redirect_url) {
                        // Redirigir automáticamente al segundo formulario grande de protocolo
                        window.location.href = data.redirect_url;
                    }
                });
            });

            var currentMiniYear = 2026;
            var currentMiniMonth = 10;

            function updateMiniCalendar(year, month) {
                var monthNames = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
                document.getElementById('mini-cal-month-title').innerText = monthNames[month - 1] + ' ' + year;

                fetch(`/api/events/dates?year=${year}&month=${month}`)
                    .then(response => response.json())
                    .then(eventDates => {
                        var firstDay = new Date(year, month - 1, 1);
                        var lastDay = new Date(year, month, 0);
                        
                        var startingDay = firstDay.getDay();
                        var totalDays = lastDay.getDate();

                        var tbody = document.getElementById('mini-cal-body');
                        tbody.innerHTML = '';

                        var row = document.createElement('tr');
                        var realToday = new Date();
                        realToday.setHours(0,0,0,0);

                        // Obtener los límites exactos de la vista activa del calendario grande
                        var viewStart = calendar.view.currentStart;
                        var viewEnd = calendar.view.currentEnd;

                        // Días del mes anterior para rellenar
                        var prevMonthLastDay = new Date(year, month - 1, 0).getDate();
                        for (var i = startingDay - 1; i >= 0; i--) {
                            var td = document.createElement('td');
                            td.innerText = prevMonthLastDay - i;
                            td.style.color = '#d1d5db';
                            row.appendChild(td);
                        }

                        // Llenar días del mes actual
                        for (var day = 1; day <= totalDays; day++) {
                            if (row.children.length === 7) {
                                tbody.appendChild(row);
                                row = document.createElement('tr');
                            }

                            var td = document.createElement('td');
                            td.innerText = day;
                            td.className = 'mc-hover';

                            var monthFormatted = String(month).padStart(2, '0');
                            var dayFormatted = String(day).padStart(2, '0');
                            var fullDateStr = `${year}-${monthFormatted}-${dayFormatted}`;
                            
                            td.setAttribute('data-date', fullDateStr);

                            var cellDate = new Date(year, month - 1, day);
                            cellDate.setHours(0,0,0,0);

                            // Marcar el día de hoy real con borde azul (siempre visible)
                            var cellTime = cellDate.getTime();
                            var realTodayTime = realToday.getTime();
                            if (cellTime === realTodayTime) {
                                td.classList.add('mc-today-real');
                            }

                            // Si hay eventos en la BD, pintar de naranja
                            if (Array.isArray(eventDates) && eventDates.includes(fullDateStr)) {
                                td.classList.add('mc-has-event');
                            }

                            // Comprobar si este día pertenece a la semana activa del calendario grande para marcar la fila en plomito de forma precisa
                            if (viewStart && viewEnd) {
                                var startTime = viewStart.getTime();
                                var endTime = viewEnd.getTime();
                                if (cellTime >= startTime && cellTime < endTime) {
                                    row.classList.add('mc-row-selected');
                                }
                            }

                            // Clic para cambiar de fecha en el calendario grande de inmediato
                            td.addEventListener('click', function() {
                                var selectedDate = this.getAttribute('data-date');
                                calendar.gotoDate(selectedDate);
                            });

                            row.appendChild(td);
                        }

                        // Rellenar días del siguiente mes
                        var nextMonthDay = 1;
                        while (row.children.length < 7) {
                            var td = document.createElement('td');
                            td.innerText = nextMonthDay++;
                            td.style.color = '#d1d5db';
                            row.appendChild(td);
                        }
                        tbody.appendChild(row);
                    }).catch(err => {
                        console.error("Error cargando fechas de eventos:", err);
                    });
            }

            document.getElementById('mini-prev').addEventListener('click', function() {
                currentMiniMonth--;
                if (currentMiniMonth < 1) { currentMiniMonth = 12; currentMiniYear--; }
                updateMiniCalendar(currentMiniYear, currentMiniMonth);
            });

            document.getElementById('mini-next').addEventListener('click', function() {
                currentMiniMonth++;
                if (currentMiniMonth > 12) { currentMiniMonth = 1; currentMiniYear++; }
                updateMiniCalendar(currentMiniYear, currentMiniMonth);
            });

            updateMiniCalendar(currentMiniYear, currentMiniMonth);

            // Menú de Vistas
            var btnDropdown = document.getElementById('btn-view-dropdown');
            var viewMenu = document.getElementById('view-menu');
            var currentViewText = document.getElementById('current-view-text');
            var viewOptions = document.querySelectorAll('.view-option');

            btnDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                viewMenu.style.display = viewMenu.style.display === 'none' ? 'block' : 'none';
            });

            document.addEventListener('click', function() {
                viewMenu.style.display = 'none';
            });

            viewOptions.forEach(function(option) {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    var viewName = this.getAttribute('data-view');

                    calendar.changeView(viewName);
                    currentViewText.innerText = this.innerText;

                    viewOptions.forEach(function(opt) {
                        opt.style.fontWeight = 'normal';
                        opt.style.backgroundColor = 'transparent';
                        opt.style.color = 'inherit';
                    });

                    this.style.fontWeight = 'bold';
                    this.style.backgroundColor = '#f9fafb';
                    this.style.color = '#1f2937';

                    viewMenu.style.display = 'none';
                });
            });

            document.getElementById('btn-prev').addEventListener('click', function() { calendar.prev(); });
            document.getElementById('btn-next').addEventListener('click', function() { calendar.next(); });
            document.getElementById('btn-hoy').addEventListener('click', function() { calendar.today(); });
        });
    </script>
</x-app-layout>