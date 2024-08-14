<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabeceraVenta extends Model
{
    use HasFactory;

    protected $table = 'cabecera_ventas';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function detalleVenta()
    {
        return $this->belongsTo(DetalleVenta::class, 'id_venta');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo');
    }
}