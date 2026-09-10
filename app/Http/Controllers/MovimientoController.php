<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class MovimientoController extends Controller
{
    private function getFilteredQuery(Request $request)
    {
        $query = Movimiento::where('user_id', Auth::id())->with('categoria');

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $movimientos = $query->latest('fecha')->paginate(10)->appends($request->all());
        $categorias = Categoria::where('user_id', Auth::id())->get();

        return view('movimientos.index', compact('movimientos', 'categorias'));
    }

    public function exportarPdf(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $movimientos = $query->latest('fecha')->get();

        $totalIngresos = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $totalGastos = $movimientos->where('tipo', 'gasto')->sum('monto');
        $balance = $totalIngresos - $totalGastos;

        $pdf = Pdf::loadView('movimientos.pdf', compact('movimientos', 'totalIngresos', 'totalGastos', 'balance'));
        return $pdf->download('reporte_movimientos.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'monto' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
        ]);

        Movimiento::create([
            'user_id' => Auth::id(),
            'categoria_id' => $request->categoria_id,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        return redirect()->back()->with('success', 'Movimiento registrado correctamente');
    }

    public function update(Request $request, Movimiento $movimiento)
    {
        if ($movimiento->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'monto' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:ingreso,gasto',
            'descripcion' => 'nullable|string|max:255',
            'fecha' => 'required|date',
        ]);

        $movimiento->update([
            'categoria_id' => $request->categoria_id,
            'monto' => $request->monto,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        return redirect()->back()->with('success', 'Movimiento actualizado correctamente');
    }

    public function destroy(Movimiento $movimiento)
    {
        if ($movimiento->user_id !== Auth::id()) {
            abort(403);
        }

        $movimiento->delete();

        return redirect()->back()->with('success', 'Movimiento eliminado correctamente');
    }
}