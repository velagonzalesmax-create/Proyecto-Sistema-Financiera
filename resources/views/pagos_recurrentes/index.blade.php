<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Pagos Recurrentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de Estado / Alertas -->
            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Formulario para Programar Pago Recurrente -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Programar Nuevo Pago Recurrente</h3>
                <form action="{{ route('pagos_recurrentes.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Servicio de Luz, Netflix, Alquiler">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select name="categoria_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Seleccione una categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }} ({{ strtoupper($cat->tipo) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto (S/)</label>
                        <input type="number" step="0.01" min="0.01" name="monto" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia</label>
                        <select name="frecuencia" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="mensual" selected>Mensual</option>
                            <option value="semanal">Semanal</option>
                            <option value="anual">Anual</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Próxima Fecha de Vencimiento</label>
                        <input type="date" name="fecha_vencimiento" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Días de Preaviso</label>
                        <input type="number" name="dias_preaviso" value="3" min="1" max="30" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. 3">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-3 pt-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 shadow-sm text-sm">
                            Programar Pago Recurrente
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Pagos Recurrentes -->
            <div class="p-6 bg-white shadow sm:rounded-lg overflow-x-auto">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Pagos Recurrentes Programados</h3>
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                            <th class="p-3">Nombre</th>
                            <th class="p-3">Categoría</th>
                            <th class="p-3">Monto</th>
                            <th class="p-3">Frecuencia</th>
                            <th class="p-3">Vencimiento</th>
                            <th class="p-3">Preaviso</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse($pagos as $pago)
                            <tr>
                                <td class="p-3 font-medium text-gray-900">{{ $pago->nombre }}</td>
                                <td class="p-3">{{ $pago->categoria->nombre ?? '-' }}</td>
                                <td class="p-3 font-bold text-gray-800">S/ {{ number_format($pago->monto, 2) }}</td>
                                <td class="p-3">{{ ucfirst($pago->frecuencia) }}</td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') }}</td>
                                <td class="p-3">{{ $pago->dias_preaviso }} días antes</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $pago->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($pago->estado) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form id="form-delete-pago-{{ $pago->id }}" action="{{ route('pagos_recurrentes.destroy', $pago->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="button" 
                                            onclick="confirmarEliminarPago({{ $pago->id }}, @js($pago->nombre), '{{ number_format($pago->monto, 2) }}')" 
                                            class="text-red-600 hover:text-red-900 font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">No hay pagos recurrentes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script de Confirmación con SweetAlert2 para Pagos Recurrentes -->
    <script>
        function confirmarEliminarPago(id, nombre, monto) {
            Swal.fire({
                title: '¿Eliminar pago recurrente?',
                html: `
                    <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm space-y-2 my-2">
                        <p><strong class="text-gray-700">Nombre:</strong> <span class="font-bold text-gray-900">${nombre}</span></p>
                        <p><strong class="text-gray-700">Monto:</strong> <span class="font-bold text-gray-900">S/ ${monto}</span></p>
                    </div>
                    <p class="text-xs text-red-500 font-medium mt-3">⚠️ Se cancelará el recordatorio programado.</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-pago-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
