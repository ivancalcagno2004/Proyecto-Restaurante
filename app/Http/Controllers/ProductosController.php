<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        // Crear el producto
        Productos::create($request->all());

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Productos::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productos = Productos::all(); // Carga todas las mesas
        $productoEdit = Productos::findOrFail($id); // Busca la mesa específica por ID
        $quiereEditar = true; // Define la variable para indicar que se está editando

        return view('productos.index', compact('productos', 'productoEdit', 'quiereEditar')); // Pasa las variables a la vista
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $producto = Productos::findOrFail($id);

        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'categoria' => 'nullable|in:entrada,plato_principal,bebida,postre',
            'stock' => 'nullable|integer|min:0',
        ]);

        // Buscar el producto y actualizarlo
        $producto->update($request->all());
        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el producto y eliminarlo
        $producto = Productos::findOrFail($id);
        $producto->delete();

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
