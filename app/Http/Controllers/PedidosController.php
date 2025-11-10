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
        foreach ($request->productos as $productoData) {
            $productoId = $productoData['id']; // Obtener el ID del producto desde el array
            $producto = Productos::findOrFail($productoId); // Buscar el producto en la base de datos
            $cantidad = $productoData['cantidad']; // Obtener la cantidad

            // Verificar si hay suficiente stock
            if ($producto->stock < $cantidad) {
                return redirect()->back()->withErrors(['error' => "No hay suficiente stock para el producto {$producto->nombre}."]);
            }

            $subtotal = $producto->precio * $cantidad; // Calcular el subtotal
            $total += $subtotal; // Sumar al total del pedido

            // Reducir el stock del producto
            $producto->decrement('stock', $cantidad);

            // Asociar el producto al pedido en la tabla pivote
            $pedido->productos()->attach($productoId, [
                'cantidad' => $cantidad,
                'subtotal' => $subtotal,
            ]);
        }

        // Actualizar el total del pedido
        $pedido->update(['total' => $total]);

        // Cambiar el estado de la mesa a "ocupada"
        $mesa = Mesas::findOrFail($request->mesa_id);
        $mesa->update(['estado' => 'ocupada']);

        // Redirigir al índice de pedidos con un mensaje de éxito
        return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pedido = Pedidos::with('productos')->findOrFail($id);
        if ($pedido->estado === 'en_preparacion') {
            $pedido->estado = 'Preparando';
        }

        // Recalcular subtotales dinámicamente
        foreach ($pedido->productos as $producto) {
            $producto->pivot->subtotal = $producto->precio * $producto->pivot->cantidad;
        }
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pedidos = Pedidos::all();
        $pedidoEdit = Pedidos::findOrFail($id);
        $quiereEditar = true;
        return view('pedidos.index', compact('pedidoEdit', 'quiereEditar', 'pedidos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,servido,cancelado',
        ]);

        $pedido = Pedidos::findOrFail($id);
        $pedido->update(['estado' => $request->estado]);

        return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedidos::findOrFail($id);
        $mesa = Mesas::findOrFail($pedido->mesa_id);
        $mesa->update(['estado' => 'disponible']);
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente.');
    }

    public function editProductos($id)
    {
        $pedido = Pedidos::with('productos')->findOrFail($id); // Cargar el pedido con los productos asociados
        $productos = Productos::all(); // Obtener todos los productos disponibles
        return view('pedidos.edit-productos', compact('pedido', 'productos'));
    }

    public function updateProductos(Request $request, $id)
    {
        $pedido = Pedidos::findOrFail($id);

        // Actualizar productos existentes
        if ($request->has('productos')) {
            foreach ($request->productos as $productoId => $productoData) {
                $producto = Productos::findOrFail($productoId);

                if (isset($productoData['eliminar']) && $productoData['eliminar'] == 1) {
                    // Devolver el stock del producto eliminado
                    $cantidadAnterior = $pedido->productos()->where('producto_id', $productoId)->first()->pivot->cantidad;
                    $producto->increment('stock', $cantidadAnterior);

                    // Eliminar producto del pedido
                    $pedido->productos()->detach($productoId);
                } else {
                    // Actualizar cantidad y subtotal del producto existente
                    $cantidadNueva = $productoData['cantidad'];
                    $cantidadAnterior = $pedido->productos()->where('producto_id', $productoId)->first()->pivot->cantidad;

                    // Ajustar el stock según la diferencia de cantidades
                    if ($cantidadNueva > $cantidadAnterior) {
                        $diferencia = $cantidadNueva - $cantidadAnterior;

                        if ($producto->stock < $diferencia) {
                            return redirect()->back()->withErrors(['error' => "No hay suficiente stock para el producto {$producto->nombre}."]);
                        }

                        $producto->decrement('stock', $diferencia);
                    } elseif ($cantidadNueva < $cantidadAnterior) {
                        $diferencia = $cantidadAnterior - $cantidadNueva;
                        $producto->increment('stock', $diferencia);
                    }

                    $subtotal = $producto->precio * $cantidadNueva;
                    $pedido->productos()->updateExistingPivot($productoId, [
                        'cantidad' => $cantidadNueva,
                        'subtotal' => $subtotal,
                    ]);
                }
            }
        }

        // Agregar nuevos productos
        if ($request->has('nuevos_productos')) {
            foreach ($request->nuevos_productos as $productoId => $productoData) {
                $producto = Productos::findOrFail($productoId);
                $cantidad = $productoData['cantidad'];

                // Verificar si hay suficiente stock
                if ($producto->stock < $cantidad) {
                    return redirect()->back()->withErrors(['error' => "No hay suficiente stock para el producto {$producto->nombre}."]);
                }

                $subtotal = $producto->precio * $cantidad;
                $producto->decrement('stock', $cantidad);

                // Asociar el producto al pedido
                $pedido->productos()->attach($productoId, [
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ]);
            }
        }

        // Recalcular el total del pedido directamente desde la base de datos
        $total = $pedido->productos()->sum('pedido_detalles.subtotal');
        $pedido->update(['total' => $total]);

        return redirect()->route('pedidos.index')->with('success', 'Productos del pedido actualizados correctamente.');
    }

    public function getPedidoByMesa($id)
    {
        // Buscar el pedido asociado a la mesa
        $pedido = Pedidos::where('mesa_id', $id)->where('estado', '!=', 'completado')->first();

        if ($pedido) {
            return response()->json(['pedido_id' => $pedido->id]);
        }

        return response()->json(['error' => 'No se encontró un pedido asociado a esta mesa.'], 404);
    }
}
