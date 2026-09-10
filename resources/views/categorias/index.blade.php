<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de Estado / Alertas -->
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

            <!-- Formulario para Crear Categoría -->
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Nueva Categoría</h3>
                <form action="{{ route('categorias.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Alimentación, Sueldo, Transporte">
                    </div>
                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="tipo" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold shadow-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Categorías -->
            <div class="p-6 bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Mis Categorías</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b text-sm font-semibold text-gray-600 bg-gray-50">
                            <th class="p-3">NOMBRE</th>
                            <th class="p-3">TIPO</th>
                            <th class="p-3 text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm text-gray-700">
                        @forelse ($categorias as $cat)
                            <tr>
                                <td class="p-3 font-medium">{{ $cat->nombre }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cat->tipo === 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($cat->tipo) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <!-- Botón Eliminar con SweetAlert2 -->
                                    <form id="form-delete-cat-{{ $cat->id }}" action="{{ route('categorias.destroy', $cat) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="button" 
                                            onclick="confirmarEliminarCategoria({{ $cat->id }}, @js($cat->nombre), '{{ ucfirst($cat->tipo) }}')" 
                                            class="text-red-600 hover:text-red-900 font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">No hay categorías registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script de Confirmación con SweetAlert2 para Categorías -->
    <script>
        function confirmarEliminarCategoria(id, nombre, tipo) {
            const isIngreso = tipo.toLowerCase() === 'ingreso';
            const colorTipo = isIngreso ? '#10B981' : '#EF4444';

            Swal.fire({
                title: '¿Eliminar categoría?',
                html: `
                    <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm space-y-2 my-2">
                        <p><strong class="text-gray-700">Categoría:</strong> <span class="font-bold text-gray-900">${nombre}</span></p>
                        <p><strong class="text-gray-700">Tipo:</strong> <span style="color: ${colorTipo}; font-weight: 700;">${tipo}</span></p>
                    </div>
                    <p class="text-xs text-amber-600 font-medium mt-3">⚠️ Solo se podrá eliminar si no contiene movimientos asociados.</p>
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
                    document.getElementById('form-delete-cat-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>