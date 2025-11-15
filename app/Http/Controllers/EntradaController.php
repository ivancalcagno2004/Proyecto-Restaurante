<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Pedidos;
use Illuminate\Http\Request;

class EntradaController extends Controller
{

    public function setPrecioEntrada(Request $request)
    {
        $request->validate([
            'precioEntrada' => 'required|numeric|min:0',
        ]);

        // Actualizar el precio de entrada usando el singleton
        Entrada::setPrecio($request->precioEntrada);

        return redirect()->route('pedidos.index')->with('success', 'Precio de entrada actualizado correctamente.');
    }

    public function edit()
    {
        // Obtener el precio de entrada actual desde el singleton
        $precioEntrada = Entrada::getPrecio();

        return view('entrada.edit', compact('precioEntrada'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'precioEntrada' => 'required|numeric|min:0',
        ]);

        // Actualizar el precio de entrada usando el singleton
        Entrada::setPrecio($request->precioEntrada);

        // Actualizar el total de todos los pedidos
        $pedidos = Pedidos::all();
        foreach ($pedidos as $pedido) {
            $totalProductos = $pedido->productos()->sum('pedido_detalles.subtotal'); // Sumar subtotales de productos
            $nuevoTotal = ($request->precioEntrada * $pedido->cant_personas) + $totalProductos;

            $pedido->update(['total' => $nuevoTotal]);
        }

        return redirect()->route('entrada.edit')->with('success', 'Precio de entrada actualizado correctamente y totales recalculados.');
    }
}
