<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
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
        $pedidos = Pedidos::orderBy('created_at', 'desc')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($mesaId)
    {
        $mesa = Mesas::findOrFail($mesaId);
        $productos = Productos::orderBy('categoria')->orderBy('nombre')->get()->groupBy('categoria'); // Obtener todos los productos disponibles
        $categorias = $productos->keys()->toArray();
        return view('pedidos.create', compact('mesa', 'productos', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'mesa_id' => 'required|exists:mesas,id', // Verificar que la mesa exista
            'cant_personas' => 'required|integer|min:1', // Validar cant_personas
            'productos' => 'required|array', // Verificar que se envíen productos
            'productos.*.id' => 'required|exists:productos,id', // Verificar que cada producto exista
            'productos.*.cantidad' => 'required|integer|min:1', // Verificar que la cantidad sea válida
        ]);

        // Crear el pedido
        $pedido = Pedidos::create([
            'mesa_id' => $request->mesa_id,
            'cant_personas' => $request->cant_personas,
            'estado' => 'pendiente', // Estado inicial del pedido
            'total' => 0, // Se calculará más adelante
        ]);

        $total = Entrada::getPrecio() * $request->cant_personas; // Incluir el precio de la entrada en el total

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
        $pedido = Pedidos::with(['productos' => function ($query) {
            $query->orderBy('pedido_detalles.cantidad', 'desc'); // Ordenar por cantidad
        }])->findOrFail($id);

        if ($pedido->estado === 'en_preparacion') {
            $pedido->estado = 'Preparando';
        }

        // Recalcular subtotales dinámicamente
        foreach ($pedido->productos as $producto) {
            $producto->pivot->subtotal = $producto->precio * $producto->pivot->cantidad;
        }

        $precioEntrada = Entrada::getPrecio();

        return view('pedidos.show', compact('pedido', 'precioEntrada'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pedidos = Pedidos::orderBy('created_at', 'desc')->get();
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
            'estado' => 'required|in:pendiente,en_preparacion,servido,facturado',
            'cant_personas' => 'required|integer|min:1',
        ]);

        $pedido = Pedidos::findOrFail($id);
        if ($request->estado === 'facturado') {
            $mesa = Mesas::findOrFail($pedido->mesa_id);
            $mesa->update(['estado' => 'disponible']);
        }

        // Actualizar la cantidad de personas y recalcular el total si se envía
        if ($request->has('cant_personas')) {
            $pedido->cant_personas = $request->cant_personas;

            // Obtener el precio de entrada desde el modelo Entrada
            $precioEntrada = Entrada::getPrecio();

            // Recalcular el total del pedido
            $totalProductos = $pedido->productos()->sum('pedido_detalles.subtotal'); // Sumar subtotales de productos
            $total = ($precioEntrada * $pedido->cant_personas) + $totalProductos;

            $pedido->update([
                'cant_personas' => $pedido->cant_personas,
                'total' => $total, // Actualizar el total
                'estado' => $request->estado,
            ]);
        }

        return redirect()->route('pedidos.index')->with('success', 'Estado del pedido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedidos::findOrFail($id);
        // Verificar si hay otros pedidos no facturados para la misma mesa
        $otrosPedidos = Pedidos::where('mesa_id', $pedido->mesa_id)
            ->where('id', '!=', $pedido->id) // Excluir el pedido actual
            ->where('estado', '!=', 'facturado') // Buscar pedidos no facturados
            ->exists();

        if (!$otrosPedidos) {
            // Si no hay otros pedidos no facturados, cambiar el estado de la mesa a "disponible"
            $mesa = Mesas::findOrFail($pedido->mesa_id);
            $mesa->update(['estado' => 'disponible']);
        }

        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente.');
    }

    public function editProductos($id)
    {
        $pedido = Pedidos::with(['productos' => function ($query) {
            $query->orderBy('pedido_detalles.cantidad', 'desc'); // Ordenar por cantidad
        }])->findOrFail($id);

        // Obtener los IDs de los productos que ya están en el pedido
        $productosEnPedido = $pedido->productos->pluck('id')->toArray();

        // Obtener todos los productos disponibles, excluyendo los que ya están en el pedido
        $productos = Productos::whereNotIn('id', $productosEnPedido)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get()
            ->groupBy('categoria'); // Agrupar por categoría

        $categorias = $productos->keys()->toArray();

        return view('pedidos.edit-productos', compact('pedido', 'productos', 'categorias'));
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
        $pedido = Pedidos::where('mesa_id', $id)->where('estado', '!=', 'facturado')->first();

        if ($pedido) {
            return response()->json(['pedido_id' => $pedido->id]);
        }

        return response()->json(['error' => 'No se encontró un pedido asociado a esta mesa.'], 404);
    }

    public function selectMesa()
    {
        $mesas = Mesas::where('estado', 'disponible')->get();
        return view('pedidos.select-mesa', compact('mesas'));
    }
}
