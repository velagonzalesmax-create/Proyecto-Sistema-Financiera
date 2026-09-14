<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MovimientoController;
use App\Models\Movimiento;
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

    return view('dashboard', compact('ingresos', 'gastos', 'balance', 'ultimosMovimientos', 'catNombres', 'catMontos'));
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
});

require __DIR__ . '/auth.php';

// Ruta secreta temporal para ejecutar migraciones directamente
Route::get('/ejecutar-migraciones-secretas', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return '<h1>¡Migraciones ejecutadas exitosamente en Aiven!</h1><pre>' . Artisan::output() . '</pre>';
    } catch (\Throwable $e) {
        return '<h1>Error en la migración:</h1><p><b>' . e($e->getMessage()) . '</b></p><pre>' . e($e->getTraceAsString()) . '</pre>';
    }
});