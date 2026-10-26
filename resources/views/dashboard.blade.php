<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Tarjetas Superiores (KPIs) con Iconos Modernos y Comparación Mensual -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KPI: Total Ingresos -->
                <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Ingresos</p>
                            <p class="text-2xl lg:text-3xl font-extrabold text-emerald-600 mt-2">
                                S/ {{ number_format($ingresos ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-2xl">
                            <!-- Icono Moderno: Ingresos -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Indicador Porcentual Mensual -->
                    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-xs">
                        @if(($diffIngresos ?? 0) >= 0)
                            <span class="inline-flex items-center font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↑ +{{ number_format($diffIngresos ?? 0, 1) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↓ {{ number_format($diffIngresos ?? 0, 1) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 font-medium">vs. mes anterior</span>
                    </div>
                </div>

                <!-- KPI: Total Gastos -->
                <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Gastos</p>
                            <p class="text-2xl lg:text-3xl font-extrabold text-red-600 mt-2">
                                S/ {{ number_format($gastos ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="p-3.5 bg-red-50 text-red-600 rounded-2xl">
                            <!-- Icono Moderno: Gastos -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Indicador Porcentual Mensual -->
                    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-xs">
                        @if(($diffGastos ?? 0) > 0)
                            <span class="inline-flex items-center font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↑ +{{ number_format($diffGastos ?? 0, 1) }}%
                            </span>
                        @elseif(($diffGastos ?? 0) < 0)
                            <span class="inline-flex items-center font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↓ {{ number_format($diffGastos ?? 0, 1) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center font-bold text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md mr-1.5">
                                0.0%
                            </span>
                        @endif
                        <span class="text-gray-400 font-medium">vs. mes anterior</span>
                    </div>
                </div>

                <!-- KPI: Balance Total -->
                <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Balance Total</p>
                            <p class="text-2xl lg:text-3xl font-extrabold mt-2 {{ ($balance ?? 0) >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                S/ {{ number_format($balance ?? 0, 2) }}
                            </p>
                        </div>
                        <div class="p-3.5 bg-blue-50 text-blue-600 rounded-2xl">
                            <!-- Icono Moderno: Balance / Billetera -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Indicador Porcentual Mensual -->
                    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-xs">
                        @if(($diffBalance ?? 0) >= 0)
                            <span class="inline-flex items-center font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↑ +{{ number_format($diffBalance ?? 0, 1) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md mr-1.5">
                                ↓ {{ number_format($diffBalance ?? 0, 1) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 font-medium">vs. mes anterior</span>
                    </div>
                </div>
            </div>

            <!-- 2. Resumen de Pagos Recurrentes -->
            <div class="bg-white p-6 shadow-sm rounded-xl border border-gray-100 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Resumen de Pagos Recurrentes
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Control de vencimientos y compromisos financieros mensuales</p>
                    </div>
                    <a href="{{ route('pagos-recurrentes.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition ease-in-out duration-150">
                        <span>Gestionar Pagos</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Tarjeta 1: Pagos Vencidos (Rojo) -->
                    <div class="p-5 bg-white shadow-sm rounded-xl border-l-4 border-red-500 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-red-600 uppercase tracking-wider">Pagos Vencidos</p>
                            <p class="text-2xl font-extrabold text-red-600 mt-1">{{ $pagosVencidos ?? 0 }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Vencimiento anterior a hoy</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-xl text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Próximos a Vencer (Amarillo / Naranja) -->
                    <div class="p-5 bg-white shadow-sm rounded-xl border-l-4 border-amber-500 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Próximos a Vencer</p>
                            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $pagosPorVencer ?? 0 }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">En los próximos 7 días</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Total Recurrente Mensual (Azul / Verde) -->
                    <div class="p-5 bg-white shadow-sm rounded-xl border-l-4 border-emerald-500 border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total Recurrente Mensual</p>
                            <p class="text-2xl font-extrabold text-emerald-600 mt-1">S/ {{ number_format($totalRecurrenteMes ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Compromisos activos</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Gráficos Estadísticos con Chart.js (Organizados en 2 Columnas lg:grid-cols-2) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico de Gastos por Categoría (Donut) -->
                <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-50">
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Gastos por Categoría</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Distribución porcentual de tus egresos</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            Gráfico Circular
                        </span>
                    </div>
                    <div class="relative h-80 w-full flex justify-center items-center">
                        <canvas id="chartCategorias"></canvas>
                    </div>
                </div>

                <!-- Gráfico Comparativo Ingresos vs Gastos (Barras) -->
                <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-50">
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Comparativa: Ingresos vs. Gastos</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Relación global entre ingresos y egresos registrados</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                            Barras
                        </span>
                    </div>
                    <div class="relative h-80 w-full flex justify-center items-center">
                        <canvas id="chartComparativa"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4. Últimos Movimientos -->
            <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100 overflow-x-auto">
                <div class="mb-4 pb-2 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Últimas Transacciones</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Historial de las 5 operaciones más recientes</p>
                    </div>
                    <a href="{{ route('movimientos.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Ver todas &rarr;
                    </a>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/70 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3 text-left rounded-l-lg">Fecha</th>
                            <th class="px-4 py-3 text-left">Categoría</th>
                            <th class="px-4 py-3 text-left">Descripción</th>
                            <th class="px-4 py-3 text-right rounded-r-lg">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($ultimosMovimientos ?? [] as $mov)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $mov->categoria->nombre ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                                    {{ $mov->descripcion ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold whitespace-nowrap {{ $mov->tipo === 'ingreso' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $mov->tipo === 'ingreso' ? '+' : '-' }} S/ {{ number_format($mov->monto, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-sm text-gray-400 text-center">
                                    No hay movimientos recientes registrados.
                                </td>
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
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 16,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    cutout: '65%'
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
                        borderRadius: 8,
                        maxBarThickness: 60,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'S/ ' + value.toLocaleString();
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: 'bold'
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
