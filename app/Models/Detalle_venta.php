<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_venta extends Model
{
    use HasFactory;
    public function producto(){
        return $this->belongsTo(Producto::class);

    }

    public function venta(){
        return $this->belongsTo(Venta::class);

    }

    public function detalleProducto(){
        return $this->belongsTo(Detalle_producto::class);

    }
}
