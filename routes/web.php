<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PagoRecurrenteController;
use App\Models\Movimiento;
use App\Models\PagoRecurrente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user_id = Auth::id();
    
    // Calcular totales de ingresos, gastos y balance
    $ingresos = Movimiento::where('user_id', $user_id)->where('tipo', 'ingreso')->sum('monto');
    $gastos = Movimiento::where('user_id', $user_id)->where('tipo', 'gasto')->sum('monto');
    $balance = $ingresos - $gastos;

    // Obtener las últimas 5 transacciones con su categoría
    $ultimosMovimientos = Movimiento::where('user_id', $user_id)
        ->with('categoria')
        ->latest('fecha')
        ->take(5)
        ->get();

    // Gastos agrupados por categoría para gráfico donut
    $gastosPorCategoria = Movimiento::where('movimientos.user_id', $user_id)
        ->where('movimientos.tipo', 'gasto')
        ->join('categorias', 'movimientos.categoria_id', '=', 'categorias.id')
        ->selectRaw('categorias.nombre as categoria, SUM(movimientos.monto) as total')
        ->groupBy('categorias.nombre')
        ->pluck('total', 'categoria');

    $catNombres = $gastosPorCategoria->keys()->toArray();
    $catMontos = $gastosPorCategoria->values()->toArray();

    // Comparación porcentual con el mes anterior para KPIs
    $inicioMesActual = Carbon::now()->startOfMonth();
    $finMesActual = Carbon::now()->endOfMonth();
    $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
    $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();

    $ingresosMesActual = Movimiento::where('user_id', $user_id)
        ->where('tipo', 'ingreso')
        ->whereBetween('fecha', [$inicioMesActual->toDateString(), $finMesActual->toDateString()])
        ->sum('monto');

    $ingresosMesAnterior = Movimiento::where('user_id', $user_id)
        ->where('tipo', 'ingreso')
        ->whereBetween('fecha', [$inicioMesAnterior->toDateString(), $finMesAnterior->toDateString()])
        ->sum('monto');

    $diffIngresos = $ingresosMesAnterior > 0 
        ? round((($ingresosMesActual - $ingresosMesAnterior) / $ingresosMesAnterior) * 100, 1)
        : ($ingresosMesActual > 0 ? 100 : 0);

    $gastosMesActual = Movimiento::where('user_id', $user_id)
        ->where('tipo', 'gasto')
        ->whereBetween('fecha', [$inicioMesActual->toDateString(), $finMesActual->toDateString()])
        ->sum('monto');

    $gastosMesAnterior = Movimiento::where('user_id', $user_id)
        ->where('tipo', 'gasto')
        ->whereBetween('fecha', [$inicioMesAnterior->toDateString(), $finMesAnterior->toDateString()])
        ->sum('monto');

    $diffGastos = $gastosMesAnterior > 0 
        ? round((($gastosMesActual - $gastosMesAnterior) / $gastosMesAnterior) * 100, 1)
        : ($gastosMesActual > 0 ? 100 : 0);

    $balanceMesActual = $ingresosMesActual - $gastosMesActual;
    $balanceMesAnterior = $ingresosMesAnterior - $gastosMesAnterior;

    $diffBalance = $balanceMesAnterior != 0 
        ? round((($balanceMesActual - $balanceMesAnterior) / abs($balanceMesAnterior)) * 100, 1)
        : ($balanceMesActual != 0 ? 100 : 0);

    // Métricas de Pagos Recurrentes
    $hoy = Carbon::now()->startOfDay();
    $proximos7Dias = Carbon::now()->addDays(7)->endOfDay();

    $pagosVencidos = PagoRecurrente::where('user_id', $user_id)
        ->where('fecha_vencimiento', '<', $hoy)
        ->count();

    $pagosPorVencer = PagoRecurrente::where('user_id', $user_id)
        ->whereBetween('fecha_vencimiento', [$hoy, $proximos7Dias])
        ->count();

    $totalRecurrenteMes = PagoRecurrente::where('user_id', $user_id)
        ->where('estado', 'activo')
        ->sum('monto');

    return view('dashboard', compact(
        'ingresos',
        'gastos',
        'balance',
        'ultimosMovimientos',
        'catNombres',
        'catMontos',
        'diffIngresos',
        'diffGastos',
        'diffBalance',
        'pagosVencidos',
        'pagosPorVencer',
        'totalRecurrenteMes'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categorías (CRUD)
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::get('/movimientos/exportar-pdf', [MovimientoController::class, 'exportarPdf'])->name('movimientos.pdf');
    Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');
    Route::put('/movimientos/{movimiento}', [MovimientoController::class, 'update'])->name('movimientos.update');
    Route::delete('/movimientos/{movimiento}', [MovimientoController::class, 'destroy'])->name('movimientos.destroy');

    // Rutas para Pagos Recurrentes
    Route::get('/pagos-recurrentes', [PagoRecurrenteController::class, 'index'])->name('pagos_recurrentes.index');
    Route::get('/pagos-recurrentes-gestion', [PagoRecurrenteController::class, 'index'])->name('pagos-recurrentes.index');
    Route::post('/pagos-recurrentes', [PagoRecurrenteController::class, 'store'])->name('pagos_recurrentes.store');
    Route::post('/pagos-recurrentes/{id}/pagar', [PagoRecurrenteController::class, 'marcarComoPagado'])->name('pagos_recurrentes.pagar');
    Route::delete('/pagos-recurrentes/{id}', [PagoRecurrenteController::class, 'destroy'])->name('pagos_recurrentes.destroy');
});

// Ruta de prueba temporal para ejecutar notificaciones de pagos recurrentes
Route::get('/probar-correo', function () {
    try {
        Artisan::call('pagos:notificar');
        $output = Artisan::output();
        return 'Notificaciones de pagos ejecutadas con éxito.<br><pre>' . htmlspecialchars($output) . '</pre>';
    } catch (\Exception $e) {
        return 'Error al ejecutar las notificaciones: ' . $e->getMessage();
    }
});

require __DIR__ . '/auth.php';
