<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidad;

class UnidadController extends Controller
{

    public function index(Request $request)
    {
        $query = Unidad::where('estado', true);

        if ($request->has('search')) {
            $query->where('descripcion', 'like', '%' . $request->search . '%');
        }

        $unidades = $query->paginate(5);
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:30'
        ]);

        Unidad::create([
            'descripcion' => $request->input('descripcion'),
            'estado' => True,
        ]);

        return redirect()->route('unidades.index')->with('success', 'Registro realizado correctamente');
    }

    public function edit(string $id)
    {
        $unidad = Unidad::findOrFail($id);
        return view('unidades.edit', compact('unidad'));
    }

    public function update(Request $request, string $id)
    {
        $unidad = Unidad::findOrFail($id);

        $request->validate([
            'descripcion' => 'nullable|string|max:30',
        ]);

        $unidad->update($request->all());

        return redirect()->route('unidades.index')->with('success', 'Actualizaciónrealizada correctamente');
    }

    public function destroy(string $id)
    {
        $unidad = Unidad::findOrFail($id);
        $unidad->estado = '0';
        $unidad->save();
        return redirect()->route('unidades.index')->with('success', 'Eliminaciónrealizada correctamente');
    }
}
