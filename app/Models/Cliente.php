<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function cabeceraVenta()
    {
        return $this->hasOne(CabeceraVenta::class, 'id_cliente');
    }
}
