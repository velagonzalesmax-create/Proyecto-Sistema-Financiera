<?php

namespace App\Http\Controllers;

use App\Models\PagoRecurrente;
use App\Models\Categoria;
use App\Models\Movimiento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoRecurrenteController extends Controller
{
    /**
     * Muestra la lista de pagos recurrentes del usuario y las categorías para el formulario.
     */
    public function index()
    {
        $userId = Auth::id();

        $pagos = PagoRecurrente::where('user_id', $userId)
            ->with('categoria')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $categorias = Categoria::where('user_id', $userId)->get();

        return view('pagos_recurrentes.index', compact('pagos', 'categorias'));
    }

    /**
     * Guarda un nuevo pago recurrente.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'monto' => 'required|numeric|min:0.01',
            'frecuencia' => 'required|in:semanal,mensual,anual',
            'fecha_vencimiento' => 'required|date',
            'dias_preaviso' => 'required|integer|min:1|max:30',
        ]);

        PagoRecurrente::create([
            'user_id' => $userId,
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'monto' => $request->monto,
            'frecuencia' => $request->frecuencia,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'dias_preaviso' => $request->dias_preaviso,
            'estado' => 'activo',
        ]);

        return redirect()->back()->with('success', 'Pago recurrente programado con éxito.');
    }

    /**
     * Marca un pago recurrente como pagado, registra el gasto y actualiza su fecha de vencimiento.
     *
     * @param int|string $id
     */
    public function marcarComoPagado($id)
    {
        $userId = Auth::id();

        $pago = PagoRecurrente::where('user_id', $userId)->findOrFail($id);

        // Registrar un nuevo movimiento/transacción como gasto
        Movimiento::create([
            'user_id' => $userId,
            'categoria_id' => $pago->categoria_id,
            'monto' => $pago->monto,
            'tipo' => 'gasto',
            'descripcion' => 'Pago de: ' . $pago->nombre,
            'fecha' => Carbon::now()->toDateString(),
        ]);

        // Actualizar la fecha de vencimiento sumándole 1 mes con Carbon
        $pago->fecha_vencimiento = Carbon::parse($pago->fecha_vencimiento)->addMonth();
        $pago->save();

        session()->flash('success', 'Pago registrado como egreso y fecha actualizada.');

        return redirect()->back();
    }

    /**
     * Elimina un pago recurrente del usuario.
     *
     * @param int|string $id
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $pago = PagoRecurrente::where('user_id', $userId)->findOrFail($id);
        $pago->delete();

        return redirect()->back()->with('success', 'Pago recurrente eliminado.');
    }
}