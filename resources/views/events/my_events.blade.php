<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Cursos Inscritos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold">Tu Agenda Personal</h3>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">
                        &larr; Volver al catálogo de cursos
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Curso</th>
                                <th class="py-2 px-4 border-b text-left">Ubicación</th>
                                <th class="py-2 px-4 border-b text-left">Fecha de Inicio</th>
                                <th class="py-2 px-4 border-b text-left">Hora de Fin</th>
                                <th class="py-2 px-4 border-b text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myEvents as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b font-medium text-blue-700">{{ $event->title }}</td>
                                    <td class="py-2 px-4 border-b">{{ $event->auditorium->name }} <br><span class="text-xs text-gray-500">{{ $event->auditorium->location }}</span></td>
                                    <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y - H:i') }}</td>
                                    <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-400">
                                            Confirmado
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">
                                        Aún no te has inscrito a ningún curso. <br>
                                        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Explorar cursos disponibles</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>