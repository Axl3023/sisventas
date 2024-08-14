<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{

    protected $table = 'unidades';

    use HasFactory;

    # ESTOS CAMPOS PUEDEN SER ASIGNADOS EN MASA
    protected $fillable = [ # $ : indica que fillable es una propiedad de la clase
        'descripcion',
        'estado',
    ];

    public $timestamps = false;

    public function productos()
    {
        return $this->hasOne(Producto::class);
    }
}
