<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

use function Laravel\Prompts\alert;

class ClienteController extends Controller
{

    public function index(Request $request)
    {
        $query = Cliente::where('estado',true);

        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        $clientes = $query->paginate(5);

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nro_doc' => 'string|max:11|unique:clientes,nro_doc',
            'dni'=> 'required|string|max:8|unique:clientes,dni',
            'nombre' => 'required|string|max:80',
            'apellido' => 'required|string|max:80',
            'email' => 'required|email|max:100',
            'direccion' => 'required|string|max:100',
        ]);

        Cliente::create([
            'nro_doc' => $request->input('nro_doc'),
            'dni' => $request->input('dni'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'direccion' => $request->input('direccion'),
            'estado' => true,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Registro realizado correctamente');
    }

    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos de entrada
        $request->validate([
            'nro_doc' => 'string|max:11|unique:clientes,nro_doc,' . $id,
            'dni' => 'required|string|max:8|unique:clientes,dni,' . $id,
            'nombre' => 'required|string|max:80',
            'apellido' => 'required|string|max:80',
            'email' => 'required|email|max:100',
            'direccion' => 'required|string|max:100',
        ]);

        // Buscar el cliente por su id
        $cliente = Cliente::findOrFail($id);

        // Actualizar los datos del cliente
        $cliente->update([
            'nro_doc' => $request->input('nro_doc'),
            'dni' => $request->input('dni'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'direccion' => $request->input('direccion'),
        ]);

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Actualización realizada correctamente');
    }

    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = '0';
        $cliente->save();
        return redirect()->route('clientes.index')->with('success', 'Eliminación realizada correctamente');
    }

    public function buscarPorNroDoc($dni)
    {
        $cliente = Cliente::where('dni', $dni)->first();

        if ($cliente) {
            if ($cliente->estado){
                return response()->json(['exists' => true, 'cliente' => $cliente]);
            }else{

            }
        } else {
            return response()->json(['exists' => false]);
        }
    }

}
