<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tarjetas de Resumen Financiero -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Ingresos -->
                <div class="p-6 bg-white shadow sm:rounded-lg border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500">Total Ingresos</p>
                    <p class="text-2xl font-bold text-green-600">S/ {{ number_format($ingresos ?? 0, 2) }}</p>
                </div>

                <!-- Gastos -->
                <div class="p-6 bg-white shadow sm:rounded-lg border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500">Total Gastos</p>
                    <p class="text-2xl font-bold text-red-600">S/ {{ number_format($gastos ?? 0, 2) }}</p>
                </div>

                <!-- Balance -->
                <div class="p-6 bg-white shadow sm:rounded-lg border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Balance Total</p>
                    <p class="text-2xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        S/ {{ number_format($balance ?? 0, 2) }}
                    </p>
                </div>
            </div>

            <!-- Resumen de Pagos Recurrentes -->
            <div class="bg-white p-6 shadow sm:rounded-lg space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Resumen de Pagos Recurrentes
                        </h3>
                        <p class="text-xs text-gray-500">Control de vencimientos y compromisos financieros mensuales</p>
                    </div>
                    <a href="{{ route('pagos-recurrentes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-md shadow transition ease-in-out duration-150">
                        <span>Gestionar Pagos</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Tarjeta 1: Pagos Vencidos (Rojo) -->
                    <div class="p-5 bg-white shadow-sm rounded-lg border-l-4 border-red-500 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Pagos Vencidos</p>
                            <p class="text-2xl font-extrabold text-red-600 mt-1">{{ $pagosVencidos ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Vencimiento anterior a hoy</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Próximos a Vencer (Amarillo / Naranja) -->
                    <div class="p-5 bg-white shadow-sm rounded-lg border-l-4 border-amber-500 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Próximos a Vencer</p>
                            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $pagosPorVencer ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">En los próximos 7 días</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-full text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Total Recurrente Mensual (Azul / Verde) -->
                    <div class="p-5 bg-white shadow-sm rounded-lg border-l-4 border-emerald-500 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total Recurrente Mensual</p>
                            <p class="text-2xl font-extrabold text-emerald-600 mt-1">S/ {{ number_format($totalRecurrenteMes ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Compromisos activos</p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos Estadísticos con Chart.js -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gráfico de Gastos por Categoría (Donut) -->
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Gastos por Categoría</h3>
                    <div class="relative max-h-72 flex justify-center items-center">
                        <canvas id="chartCategorias"></canvas>
                    </div>
                </div>

                <!-- Gráfico Comparativo Ingresos vs Gastos (Barras) -->
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Comparativa: Ingresos vs Gastos</h3>
                    <div class="relative max-h-72 flex justify-center items-center">
                        <canvas id="chartComparativa"></canvas>
                    </div>
                </div>
            </div>

            <!-- Últimos Movimientos -->
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Últimas Transacciones</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ultimosMovimientos ?? [] as $mov)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $mov->fecha }}</td>
                                <td class="px-4 py-2 text-sm font-semibold text-gray-800">{{ $mov->categoria->nombre ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $mov->descripcion ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm font-bold {{ $mov->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center">No hay movimientos recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Inicialización de Gráficos JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Gráfico Donut de Gastos por Categoría
            const catNombres = @js($catNombres ?? []);
            const catMontos = @js($catMontos ?? []);

            const ctxCat = document.getElementById('chartCategorias').getContext('2d');
            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: catNombres.length > 0 ? catNombres : ['Sin datos'],
                    datasets: [{
                        data: catMontos.length > 0 ? catMontos : [1],
                        backgroundColor: [
                            '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', 
                            '#EC4899', '#6366F1', '#14B8A6', '#84CC16', '#64748B'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // 2. Gráfico de Barras Ingresos vs Gastos
            const totalIngresos = {{ $ingresos ?? 0 }};
            const totalGastos = {{ $gastos ?? 0 }};

            const ctxComp = document.getElementById('chartComparativa').getContext('2d');
            new Chart(ctxComp, {
                type: 'bar',
                data: {
                    labels: ['Ingresos', 'Gastos'],
                    datasets: [{
                        label: 'Monto Total (S/)',
                        data: [totalIngresos, totalGastos],
                        backgroundColor: ['#10B981', '#EF4444'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>