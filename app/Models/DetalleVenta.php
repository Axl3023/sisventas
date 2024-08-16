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
        return $this->belongsTo(CabeceraVenta::class, 'id_venta');
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
