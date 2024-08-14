<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    # ESTOS CAMPOS PUEDEN SER ASIGNADOS EN MASA
    protected $fillable = [ # $ : indica que fillable es una propiedad de la clase
        'descripcion',
        'estado',
        'stock',
        'precio',
        'id_categoria',
        'id_unidad',
    ];

    public $timestamps = false;

    public function detalleVenta()
    {
        return $this->belongsTo(DetalleVenta::class, 'id_venta');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad');
    }
}
