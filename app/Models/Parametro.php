<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parametro extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo';
    public $timestamps = false;
    protected $guarded = []; 

    public function tipodocumento()
    {
        return $this->hasOne(TipoDocumento::class);
    }
}
