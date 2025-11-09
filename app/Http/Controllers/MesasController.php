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

    public function create()
    {
        return view('mesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
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

    public function updateEstado(Request $request, Mesas $mesa)
    {
        // Validar el estado recibido
        $request->validate([
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);

        // Actualizar el estado de la mesa
        $mesa->estado = $request->estado;
        $mesa->save();

        // Retornar una respuesta JSON
        return response()->json(['success' => true, 'estado' => $mesa->estado]);
    }
}
