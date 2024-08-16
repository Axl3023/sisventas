<?php

namespace App\Http\Controllers;

use App\Models\CabeceraVenta;
use App\Models\DetalleVenta;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{

    public function exportar($id, $tipo)
    {
        $venta = CabeceraVenta::findOrFail($id);
        $parametro = Parametro::where('id_tipo', $venta->id_tipo)->first();
        $detalles = DetalleVenta::where('id_venta', $id)->with('productos')->get();


        if ($tipo == 1) {
            // Es boleta
            $pdf = Pdf::loadView('exportar.boleta', [
                'venta' => $venta,
                'serie' => $parametro->serie,
                'detalles' => $detalles
            ]);
        } else if ($tipo == 2) {
            // Es factura
            $pdf = Pdf::loadView('exportar.factura', [
                'venta' => $venta,
                'serie' => $parametro->serie,
                'detalles' => $detalles
            ]);
        }

        return $pdf->stream();
    }
}
