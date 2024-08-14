<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Unidad;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

    public function index(Request $request)
    {
        $query = Producto::where('estado', true)->where('stock', '>', 0);

        if ($request->has('search')) {
            $query->where('descripcion', 'like', '%' . $request->search . '%');
        }

        $productos = $query->paginate(5);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::where('estado',true)->get();
        $unidades = Unidad::where('estado', true)->get();
        return view('productos.create', compact('categorias', 'unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:30',
            'stock' => 'required|numeric',
            'precio' => 'required|numeric',
            'id_categoria' => 'required|exists:categorias,id',
            'id_unidad' => 'required|exists:unidades,id',
        ]);

        Producto::create([
            'descripcion' => $request->input('descripcion'),
            'stock' => $request->input('stock'),
            'precio' => $request->input('precio'),
            'id_categoria' => $request->input('id_categoria'),
            'id_unidad' => $request->input('id_unidad'),
            'estado' => True,
        ]);

        return redirect()->route('productos.index')->with('success', 'Registro realizado correctamente');
    }

    public function edit(string $id)
    {
        $categorias = Categoria::where('estado', true)->get();
        $unidades = Unidad::where('estado', true)->get();
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto','categorias','unidades'));
    }

    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'descripcion' => 'nullable|string|max:30',
            'stock' => 'nullable|numeric',
            'precio' => 'nullable|numeric',
            'id_categoria' => 'nullable|exists:categorias,id',
            'id_unidad' => 'nullable|exists:unidades,id',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Actualización realizada correctamente');
    }

    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = '0';
        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Eliminación realizada correctamente');
    }
}
