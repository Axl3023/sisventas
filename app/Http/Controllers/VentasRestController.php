<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasRestController extends Controller
{
    public function index(){
        $ventasPorFecha = DB::table('cabecera_ventas')
        ->select(
            DB::raw('DATE(fecha_venta) as fecha_venta'),
            DB::raw('SUM(total) as total_ventas')
        )
        ->groupBy(DB::raw('DATE(fecha_venta)'))
        ->get()
        ->map(function ($item) {
            $item->total_ventas = (int) $item->total_ventas;
            return $item;
        });

        return response()->json($ventasPorFecha, 200);
    }
}
