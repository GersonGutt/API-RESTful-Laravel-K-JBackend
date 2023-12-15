<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{

    public function detalle_compra(){
        return $this->hasMany(Detalle_compra::class);

    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);

    }
    use HasFactory;
}
