<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $cantidadClientes = 0;
        $cantidadVentas = 0;

        // Contar la cantidad de clientes
        $cantidadClientes = DB::table('clientes')->count();

        // Contar la cantidad de ventas
        $cantidadVentas = DB::table('cabecera_ventas')->count();

        return view('dashboard', compact('cantidadClientes', 'cantidadVentas'));
    }
}
