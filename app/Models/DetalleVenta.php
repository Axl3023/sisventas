<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    public $timestamps = false;

    protected $guarded = [];

    public function cabeceraVentas()
    {
        return $this->hasMany(CabeceraVenta::class, 'id_venta');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_producto');
    }
}
