<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Movimientos') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, editMovimiento: { id: null, monto: '', tipo: 'gasto', categoria_id: '', fecha: '', descripcion: '' } }">
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

            <!-- Formulario de Filtros y Exportación -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filtros de Búsqueda</h3>
                    <a href="{{ route('movimientos.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar PDF
                    </a>
                </div>

                <form action="{{ route('movimientos.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                        <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="categoria_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }} ({{ strtoupper($cat->tipo) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="tipo" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todos los tipos</option>
                            <option value="ingreso" {{ request('tipo') === 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                            <option value="gasto" {{ request('tipo') === 'gasto' ? 'selected' : '' }}>Gasto</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 flex items-center space-x-3 pt-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md text-xs uppercase hover:bg-indigo-700 transition">
                            Filtrar
                        </button>
                        <a href="{{ route('movimientos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-md text-xs uppercase hover:bg-gray-300 transition">
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>

            <!-- Formulario de Nuevo Movimiento -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Registrar Movimiento</h3>
                <form action="{{ route('movimientos.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Monto</label>
                        <input type="number" step="0.01" name="monto" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="tipo" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="gasto">Gasto</option>
                            <option value="ingreso">Ingreso</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="categoria_id" class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }} ({{ strtoupper($cat->tipo) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text" name="descripcion" placeholder="Ej: Compra de supermercado" class="w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="sm:col-span-3">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded-md uppercase text-xs hover:bg-gray-700">
                            Registrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Historial -->
            <div class="p-6 bg-white shadow sm:rounded-lg overflow-x-auto">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historial de Transacciones</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($movimientos as $mov)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $mov->fecha }}</td>
                                <td class="px-4 py-2 text-sm font-semibold text-gray-800">{{ $mov->categoria->nombre ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $mov->descripcion ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm font-bold {{ $mov->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}
                                </td>
                                <td class="px-4 py-2 text-sm text-right space-x-2">
                                    <!-- Botón Editar -->
                                    <button 
                                        type="button" 
                                        @click="editMovimiento = { id: {{ $mov->id }}, monto: '{{ $mov->monto }}', tipo: '{{ $mov->tipo }}', categoria_id: '{{ $mov->categoria_id }}', fecha: '{{ $mov->fecha }}', descripcion: @js($mov->descripcion ?? '') }; openModal = true" 
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Editar
                                    </button>

                                    <!-- Botón Eliminar con SweetAlert2 -->
                                    <form id="form-delete-mov-{{ $mov->id }}" action="{{ route('movimientos.destroy', $mov) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="button" 
                                            onclick="confirmarEliminarMovimiento({{ $mov->id }}, '{{ ucfirst($mov->tipo) }}', '{{ number_format($mov->monto, 2) }}', @js($mov->categoria->nombre ?? 'Sin categoría'))" 
                                            class="text-red-600 hover:text-red-900 font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-sm text-gray-500 text-center">No hay registros aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $movimientos->links() }}
                </div>
            </div>

        </div>

        <!-- Modal Flotante para Editar Movimiento (Alpine.js) -->
        <div x-show="openModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="openModal = false"></div>

            <!-- Contenido del Modal -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6 z-10 space-y-4" @click.away="openModal = false">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-gray-800">Editar Movimiento</h3>
                        <button @click="openModal = false" type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                    </div>

                    <form :action="'{{ url('movimientos') }}/' + editMovimiento.id" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Monto</label>
                            <input type="number" step="0.01" name="monto" x-model="editMovimiento.monto" required class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="tipo" x-model="editMovimiento.tipo" required class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="gasto">Gasto</option>
                                <option value="ingreso">Ingreso</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="categoria_id" x-model="editMovimiento.categoria_id" required class="w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }} ({{ strtoupper($cat->tipo) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="fecha" x-model="editMovimiento.fecha" required class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input type="text" name="descripcion" x-model="editMovimiento.descripcion" placeholder="Ej: Compra de supermercado" class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="sm:col-span-2 flex justify-end space-x-3 pt-2">
                            <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
                                Actualizar Movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Script de Confirmación con SweetAlert2 para Movimientos -->
    <script>
        function confirmarEliminarMovimiento(id, tipo, monto, categoria) {
            const isIngreso = tipo.toLowerCase() === 'ingreso';
            const colorTipo = isIngreso ? '#10B981' : '#EF4444';
            
            Swal.fire({
                title: '¿Confirmar eliminación?',
                html: `
                    <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm space-y-2 my-2">
                        <p><strong class="text-gray-700">Tipo:</strong> <span style="color: ${colorTipo}; font-weight: 700;">${tipo}</span></p>
                        <p><strong class="text-gray-700">Categoría:</strong> <span class="font-semibold text-gray-900">${categoria}</span></p>
                        <p><strong class="text-gray-700">Monto:</strong> <span class="font-bold text-gray-900">S/ ${monto}</span></p>
                    </div>
                    <p class="text-xs text-red-500 font-medium mt-3">⚠️ Esta acción eliminará el registro y afectará tu balance financiero actual.</p>
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
                    document.getElementById('form-delete-mov-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>