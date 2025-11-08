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

    public function edit(Mesas $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    public function update(Request $request, Mesas $mesa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $mesa->update($request->all());
        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada exitosamente.');
    }

    public function destroy(Mesas $mesa)
    {
        $mesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada exitosamente.');
    }
}
