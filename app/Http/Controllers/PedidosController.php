<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;
use App\Models\Mesas;
use App\Models\Productos;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pedidos = Pedidos::all();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($mesaId)
    {
        $mesa = Mesas::findOrFail($mesaId);
        $productos = Productos::all(); // Obtener todos los productos disponibles
        return view('pedidos.create', compact('mesa', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'mesa_id' => 'required|exists:mesas,id', // Verificar que la mesa exista
            'productos' => 'required|array', // Verificar que se envíen productos
            'productos.*.id' => 'required|exists:productos,id', // Verificar que cada producto exista
            'productos.*.cantidad' => 'required|integer|min:1', // Verificar que la cantidad sea válida
        ]);

        // Crear el pedido
        $pedido = Pedidos::create([
            'mesa_id' => $request->mesa_id,
            'estado' => 'pendiente', // Estado inicial del pedido
            'total' => 0, // Se calculará más adelante
        ]);

        $total = 0;

        // Asociar productos al pedido
        foreach ($request->productos as $productoId => $productoData) {
            $producto = Productos::findOrFail($productoId); // Obtener el producto
            $subtotal = $producto->precio * $productoData['cantidad']; // Calcular el subtotal
            $total += $subtotal; // Sumar al total del pedido

            // Asociar el producto al pedido en la tabla pivote
            $pedido->productos()->attach($productoId, [
                'cantidad' => $productoData['cantidad'],
                'subtotal' => $subtotal,
            ]);
        }

        // Actualizar el total del pedido
        $pedido->update(['total' => $total]);

        // Redirigir al índice de pedidos con un mensaje de éxito
        return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedidos::with('productos')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedidos::findOrFail($id);
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente.');
    }
}
