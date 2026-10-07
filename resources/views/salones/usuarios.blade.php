<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="min-h-screen bg-white py-8 px-4 sm:px-8">
        <div style="max-width: 1100px; margin: 0 auto;">
            
            <h1 style="font-size: 26px; font-weight: bold; color: #475b75; margin-bottom: 12px;">Gestión de usuarios</h1>
            
            <p style="font-size: 13.5px; color: #4b5563; margin-bottom: 24px;">
                Los usuarios y superusuarios enumerados aquí pueden acceder a todas las aplicaciones de su cuenta.
            </p>

            <!-- TABLA PRINCIPAL DE USUARIOS -->
            <div style="border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 24px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13.5px; text-align: left;">
                    <thead>
                        <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 10px 16px; font-weight: bold; color: #475b75;">Rol</th>
                            <th style="padding: 10px 16px; font-weight: bold; color: #475b75;">Nombre de usuario / correo</th>
                            <th style="padding: 10px 16px; font-weight: bold; color: #475b75;">Nombre completo</th>
                            <th style="padding: 10px 16px; font-weight: bold; color: #475b75;">Creado el</th>
                            <th style="padding: 10px 16px; text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #e5e7eb; color: #4b5563; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 10px 16px;">{{ $user->role ?? 'usuario' }}</td>
                            <td style="padding: 10px 16px;">{{ $user->email }}</td>
                            <td style="padding: 10px 16px;">{{ $user->name }}</td>
                            <td style="padding: 10px 16px;">
                                {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td style="padding: 10px 16px; text-align: right; color: #2563eb; font-size: 15px;">
                                <!-- Botón Editar -->
                                <button onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" title="Editar" style="margin-right: 12px; color: #2563eb; background: none; border: none; cursor: pointer;">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar" style="color: #ef4444; background: none; border: none; cursor: pointer;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- BOTÓN NUEVO USUARIO -->
            @if(auth()->user()->role === 'admin')
            <button onclick="document.getElementById('modal-create').style.display='flex'" style="background-color: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 13.5px; margin-bottom: 30px; cursor: pointer;">
                Nuevo usuario
            </button>
            @endif

            <!-- ENLACE PARA VOLVER AL RESUMEN -->
            <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 24px; margin-top: 40px;">
                <a href="{{ route('salones.supervisar') }}" style="color: #2563eb; text-decoration: underline; font-size: 14px;">
                    Volver al Resumen
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL CREAR USUARIO -->
    <div id="modal-create" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; width: 400px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; font-weight: bold; color: #475b75;">Crear Nuevo Usuario</h3>
                <button onclick="document.getElementById('modal-create').style.display='none'" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #9ca3af;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('usuarios.store') }}" method="POST" style="padding: 20px;">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Nombre Completo</label>
                    <input type="text" name="name" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Correo Electrónico</label>
                    <input type="email" name="email" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Contraseña</label>
                    <input type="password" name="password" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Rol</label>
                    <select name="role" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                        <option value="usuario">Usuario (Solo ver)</option>
                        <option value="superadmin">Superadmin (Crear y editar)</option>
                        <option value="admin">Admin (Control total)</option>
                    </select>
                </div>
                <button type="submit" style="width: 100%; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer;">Guardar Usuario</button>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR USUARIO -->
    <div id="modal-edit" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; width: 400px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; font-weight: bold; color: #475b75;">Editar Usuario</h3>
                <button onclick="document.getElementById('modal-edit').style.display='none'" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #9ca3af;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="form-edit" method="POST" style="padding: 20px;">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Nombre Completo</label>
                    <input type="text" id="edit-name" name="name" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Correo Electrónico</label>
                    <input type="email" id="edit-email" name="email" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Contraseña <span style="font-weight: normal; color: #9ca3af;">(Dejar en blanco para no cambiar)</span></label>
                    <input type="password" name="password" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 13px; color: #4b5563; font-weight: bold;">Rol</label>
                    <select id="edit-role" name="role" style="width: 100%; border: 1px solid #d1d5db; border-radius: 4px; padding: 8px; font-size: 13px;" required>
                        <option value="usuario">Usuario (Solo ver)</option>
                        <option value="superadmin">Superadmin (Crear y editar)</option>
                        <option value="admin">Admin (Control total)</option>
                    </select>
                </div>
                <button type="submit" style="width: 100%; background: #2563eb; color: white; border: none; padding: 10px; border-radius: 4px; font-size: 14px; font-weight: bold; cursor: pointer;">Actualizar Usuario</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, role) {
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            
            // Si el rol viene vacío, lo marcamos como usuario por defecto
            document.getElementById('edit-role').value = role ? role : 'usuario'; 
            
            // Configurar la ruta dinámicamente para que actualice al ID correcto
            document.getElementById('form-edit').action = '/salones/usuarios/' + id;
            
            document.getElementById('modal-edit').style.display = 'flex';
        }
    </script>
</x-app-layout>