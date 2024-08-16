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

        // Buscar cliente por nro_doc o DNI
        $cliente = Cliente::where('dni', $request->input('dni'))
            ->orWhere('nro_doc', $request->input('nro_doc'))
            ->first();

        // Si no existe el cliente, lo creamos
        if (!$cliente) {
            if ($request->filled('dni1')) {
                $request->validate([
                    'nro_doc' => 'required|string',
                    'dni1' => 'required|string',
                    'fecha_venta' => 'required|date_format:Y-m-d\TH:i',
                    'id_tipo' => 'required|exists:tipo_documentos,id',
                    'id_producto.*' => 'required|exists:productos,id',
                    'cantidad.*' => 'required|integer|min:1',
                    'nombre' => 'required|string|max:80',
                    'apellido' => 'required|string|max:80',
                    'email' => 'required|email|max:100',
                    'direccion' => 'required|string|max:100',
                ]);
                // Crear cliente usando los campos correspondientes al DNI1
                $cliente = Cliente::create([
                    'nro_doc' => $request->input('nro_doc'),
                    'dni' => $request->input('dni1'),
                    'nombre' => $request->input('nombre'),
                    'apellido' => $request->input('apellido'),
                    'email' => $request->input('email'),
                    'direccion' => $request->input('direccion'),
                    'estado' => true,
                ]);
            } else {
                $request->validate([
                    'dni' => 'required|string',
                    'fecha_venta' => 'required|date_format:Y-m-d\TH:i',
                    'id_tipo' => 'required|exists:tipo_documentos,id',
                    'id_producto.*' => 'required|exists:productos,id',
                    'cantidad.*' => 'required|integer|min:1',
                    'nombre2' => 'required|string|max:80',
                    'apellido2' => 'required|string|max:80',
                    'email2' => 'required|email|max:100',
                    'direccion2' => 'required|string|max:100',
                ]);
                // Crear cliente usando los campos correspondientes al DNI2
                $cliente = Cliente::create([
                    'dni' => $request->input('dni'),
                    'nombre' => $request->input('nombre2'),
                    'apellido' => $request->input('apellido2'),
                    'email' => $request->input('email2'),
                    'direccion' => $request->input('direccion2'),
                    'estado' => true,
                ]);
            }
        } else {
            $request->validate([
                'nro_doc' => 'nullable|string',
                'dni' => 'nullable|string',
                'fecha_venta' => 'required|date_format:Y-m-d\TH:i',
                'id_tipo' => 'required|exists:tipo_documentos,id',
                'id_producto.*' => 'required|exists:productos,id',
                'cantidad.*' => 'required|integer|min:1',
            ]);
            // Validar que el cliente no esté inactivo
            if (!$cliente->estado) {
                return redirect()->back()->with('error', 'El cliente está inactivo');
            }
        }

        // Procesamiento de los productos
        $productos = $request->input('id_producto');
        $cantidades = $request->input('cantidad');

        // Verificación del stock
        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];

            if ($producto->stock < $cantidad) {
                return redirect()->back()->with('error', "No hay stock suficiente de {$producto->descripcion}. Stock disponible: {$producto->stock}, Cantidad solicitada: {$cantidad}");
            }
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

        // Obtener parámetro y actualizar numeración
        $parametro = Parametro::where('id_tipo', $request->input('id_tipo'))->first();
        $nueva_numeracion = (int)$parametro->numeracion + 1;
        $parametro->numeracion = $nueva_numeracion;
        $parametro->save();

        // Calcular el subtotal y crear los detalles de venta
        $total = 0;

        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];
            $precio = $producto->precio;

            $total += $precio * $cantidad;

            // Crear el detalle de venta
            DetalleVenta::create([
                'id_venta' => $venta->id,
                'id_producto' => $productoId,
                'precio' => $precio,
                'cantidad' => $cantidad,
            ]);

            // Actualizar el stock del producto
            $producto->update(['stock' => $producto->stock - $cantidad]);
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

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente');
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

        $productos = $request->input('id_producto');
        $cantidades = $request->input('cantidad');

        // Validar si hay productos repetidos
        if (count($productos) !== count(array_unique($productos))) {
            return redirect()->back()->with('error', 'No puedes seleccionar el mismo producto más de una vez.');
        }

        // Primer bucle: Verificación del stock
        foreach ($productos as $index => $productoId) {
            $producto = Producto::find($productoId);
            $cantidad = $cantidades[$index];

            // Verificar el stock
            if ($producto->stock < $cantidad) {
                return redirect()->back()->with('error', "No hay stock suficiente de {$producto->descripcion}. Stock disponible: {$producto->stock}, Cantidad solicitada: {$cantidad}");
            }
        }

        // Actualizar la cabecera de la venta (sin modificar el nro_doc)
        $venta->update([
            'fecha_venta' => $request->input('fecha_venta'),
            'id_tipo' => $request->input('id_tipo'),
            // No actualizar 'nro_doc' aquí
        ]);

        // Eliminar los detalles de venta existentes
        DetalleVenta::where('id_venta', $venta->id)->delete();

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
        // Buscar la venta por ID
        $venta = CabeceraVenta::findOrFail($id);

        // Obtener los detalles de la venta
        $detalles = DetalleVenta::where('id_venta', $id)->get();

        // Restaurar el stock de cada producto
        foreach ($detalles as $detalle) {
            $producto = Producto::findOrFail($detalle->id_producto);
            $producto->stock += $detalle->cantidad;
            $producto->save();
        }

        // Cambiar el estado de la venta a '0' para indicar que está cancelada
        $venta->estado = '0';
        $venta->save();

        return redirect()->route('ventas.index')->with('success', 'Eliminación realizada correctamente');
    }

}
