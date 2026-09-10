<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('user_id', Auth::id())->latest()->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:ingreso,gasto',
        ]);

        Categoria::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return redirect()->back()->with('status', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        if ($categoria->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:ingreso,gasto',
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);

        return redirect()->back()->with('status', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->user_id !== Auth::id()) {
            abort(403);
        }

        // Protección de integridad: evitar eliminar categorías que tienen movimientos asociados
        if ($categoria->movimientos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar: la categoría tiene movimientos registrados.');
        }

        $categoria->delete();

        return redirect()->back()->with('status', 'Categoría eliminada correctamente.');
    }
}