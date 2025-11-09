<?php

namespace App\Http\Controllers;

use App\Models\Mesas;
use Illuminate\Http\Request;

class MesasController extends Controller
{
    public function index()
    {
        $mesas = Mesas::all();
        return view('mesas.index', compact('mesas'));
    }

    public function map()
    {
        $mesas = Mesas::all(); // Obtén todas las mesas
        return view('pages.map', compact('mesas')); // Pasa las mesas a la vista
    }

    public function create()
    {
        return view('mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|in:disponible,ocupada,reservada',
            'capacidad' => 'required|integer|min:1|max:20',
            'x' => 'nullable|numeric',
            'y' => 'nullable|numeric',
        ]);

        Mesas::create($request->all());

        return redirect()->route('mesas.index')->with('success', 'Mesa creada exitosamente.');
    }

    public function edit($id)
    {
        $mesas = Mesas::all(); // Carga todas las mesas
        $mesaEdit = Mesas::findOrFail($id); // Busca la mesa específica por ID
        $quiereEditar = true; // Define la variable para indicar que se está editando

        return view('mesas.index', compact('mesas', 'mesaEdit', 'quiereEditar')); // Pasa las variables a la vista
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:disponible,ocupada,reservada', // Valida el estado
        ]);

        $mesa = Mesas::findOrFail($id);
        $mesa->estado = $request->estado; // Actualiza el estado
        $mesa->save();

        return redirect()->route('mesas.index')->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(Mesas $mesa)
    {
        $mesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada exitosamente.');
    }

    public function updateMesa(Request $request, $id)
    {
        // Buscar la mesa por ID
        $mesa = Mesas::findOrFail($id);

        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'estado' => 'nullable|in:disponible,ocupada,reservada',
            'capacidad' => 'nullable|integer|min:1|max:20',
            'x' => 'nullable|numeric',
            'y' => 'nullable|numeric',
        ]);

        // Actualizar los campos, manteniendo los valores existentes si no se envían
        $mesa->update(array_merge($mesa->toArray(), $request->only(['nombre', 'estado', 'capacidad', 'x', 'y'])));

        // Retornar una respuesta JSON
        return response()->json(['success' => true, 'mesa' => $mesa]);
    }
}
