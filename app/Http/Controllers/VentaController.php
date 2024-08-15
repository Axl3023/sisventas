<?php

namespace App\Http\Controllers;

use App\Models\CabeceraVenta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Parametro;
use App\Models\Producto;
use App\Models\TipoDocumento;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;

class VentaController extends Controller
{

    public function index(Request $request)
    {
        $query = CabeceraVenta::where('estado', true);

        // if ($request->has('search')) {
        //     $query->where('descripcion', 'like', '%' . $request->search . '%');
        // }

        $ventas = $query->paginate(5);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::where('estado', true)->where('stock', '>', 0)->get();
        $tipos = TipoDocumento::all();
        $parametro_1 = Parametro::where('id_tipo', 1)->first();
        $parametro_2 = Parametro::where('id_tipo', 2)->first();
        return view('ventas.create', compact('productos', 'tipos','parametro_1','parametro_2'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nro_doc' => 'required|string',
            'fecha_venta' => 'required|date_format:Y-m-d\TH:i',
            'id_tipo' => 'required|exists:tipo_documentos,id',
            'id_producto.*' => 'required|exists:productos,id',
            'cantidad.*' => 'required|integer|min:1',
            'nombre' => 'nullable|string|max:80',
            'apellido' => 'nullable|string|max:80',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:100',
        ]);

        $productos = $request->input('id_producto');
        if (count($productos) !== count(array_unique($productos))) {
            return redirect()->back()->with('error', 'No puedes seleccionar el mismo producto más de una vez.');
        }

        // Buscar o crear el cliente
        $cliente = Cliente::where('nro_doc', $request->input('nro_doc'))->first();

        // Validar cliente inactivo
        if (!$cliente->estado){
            return redirect()->back()->with('error', 'El cliente está inactivo');
        }

        // Validar que el cliente exista o que se hayan proporcionado los datos necesarios para crear uno nuevo
        if (!$cliente && (!$request->filled('nombre') || !$request->filled('apellido') || !$request->filled('email') || !$request->filled('direccion'))) {
            return redirect()->back()->with('error', 'Debe buscar el cliente o ingresar todos los datos para registrarlo.');
        }

        $productos = $request->input('id_producto');
        $cantidades = $request->input('cantidad');

        // Primer bucle: Verificación del stock
        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];

            // Verificar el stock
            if ($producto->stock < $cantidad) {
                return redirect()->back()->with('error', "No hay stock suficiente de {$producto->descripcion}. Stock disponible: {$producto->stock}, Cantidad solicitada: {$cantidad}");
            }
        }

        if (!$cliente) {
            // Crear nuevo cliente si no existe
            $cliente = Cliente::create([
                'nro_doc' => $request->input('nro_doc'),
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'email' => $request->input('email'),
                'direccion' => $request->input('direccion'),
            ]);
        }

        // Crear la cabecera de venta
        $venta = CabeceraVenta::create([
            'id_cliente' => $cliente->id,
            'fecha_venta' => $request->input('fecha_venta'),
            'id_tipo' => $request->input('id_tipo'),
            'nro_doc' => $request->input('numeracion_data'),
            'total' => 0, // Se actualizará más adelante
            'subtotal' => 0, // Se actualizará más adelante
            'igv' => 0, // Se actualizará más adelante
            'estado' => true,
        ]);

        //Obtener parametro y actualizar
        $parametro = Parametro::where('id_tipo', $request->input('id_tipo'))->first();
        $nueva_numeracion = (int)$parametro->numeracion + 1;
        $parametro->numeracion = $nueva_numeracion;
        $parametro->save();

        // Calcular el subtotal y crear los detalles de venta
        $total = 0;

        // Segundo bucle: Crear detalles de venta y actualizar el stock
        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];
            $precio = $producto->precio;

            // Calcular el total para este producto
            $total += $precio * $cantidad;

            // Crear el detalle de venta
            DetalleVenta::create([
                'id_venta' => $venta->id,
                'id_producto' => $productoId,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ]);

            // Actualizar el stock del producto
            $producto->update([
                'stock' => $producto->stock - $cantidad,
            ]);
        }

        // Calcular el IGV y el total
        $igv = $total * 0.18; // Suponiendo que el IGV es 18%
        $subtotal = $total - $igv;

        // Actualizar los totales en la cabecera de venta
        $venta->update([
            'total' => $total,
            'subtotal' => $subtotal,
            'igv' => $igv,
        ]);

        return redirect()->route('ventas.index')->with('success', 'Registro realizado correctamente');
    }

    public function edit(string $id)
    {
        $productos = Producto::where('estado', true)->where('stock', '>', 0)->get();
        $tipos = TipoDocumento::all();
        $parametro_1 = Parametro::where('id_tipo', 1)->first();
        $parametro_2 = Parametro::where('id_tipo', 2)->first();
        $venta = CabeceraVenta::findOrFail($id);
        $detallesVenta = DetalleVenta::where('id_venta', $venta->id)->get();
        return view('ventas.edit', compact('venta', 'productos', 'tipos', 'parametro_1', 'parametro_2', 'detallesVenta'));
    }

    public function update(Request $request, string $id)
    {
        $venta = CabeceraVenta::findOrFail($id);

        $request->validate([
            'nro_doc' => 'required|string',
            'fecha_venta' => 'required|date_format:Y-m-d\TH:i',
            'id_tipo' => 'required|exists:tipo_documentos,id',
            'id_producto.*' => 'required|exists:productos,id',
            'cantidad.*' => 'required|integer|min:1',
            'nombre' => 'nullable|string|max:80',
            'apellido' => 'nullable|string|max:80',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:100',
        ]);

        // Actualizar la cabecera de la venta
        $venta->update($request->only(['nro_doc', 'fecha_venta', 'id_tipo']));

        // Eliminar los detalles de venta existentes
        DetalleVenta::where('id_venta', $venta->id)->delete();

        $productos = $request->input('id_producto');
        $cantidades = $request->input('cantidad');
        $total = 0;

        // Agregar los nuevos detalles de venta
        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];
            $precio = $producto->precio;

            // Calcular el total para este producto
            $total += $precio * $cantidad;

            // Crear el detalle de venta
            DetalleVenta::create([
                'id_venta' => $venta->id,
                'id_producto' => $productoId,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ]);

            // Actualizar el stock del producto
            $producto->update([
                'stock' => $producto->stock - $cantidad,
            ]);
        }

        // Calcular el IGV y el subtotal
        $igv = $total * 0.18; // Suponiendo que el IGV es 18%
        $subtotal = $total - $igv;

        // Actualizar los totales en la cabecera de venta
        $venta->update([
            'total' => $total,
            'subtotal' => $subtotal,
            'igv' => $igv,
        ]);

        return redirect()->route('ventas.index')->with('success', 'Actualización realizada correctamente');
    }

    public function destroy(string $id)
    {
        $venta = CabeceraVenta::findOrFail($id);
        $venta->estado = '0';
        $venta->save();
        return redirect()->route('ventas.index')->with('success', 'Eliminación realizada correctamente');
    }
}
