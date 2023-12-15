<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_producto extends Model
{
    public function producto(){
        return $this->belongsTo(Producto::class);

    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);

    }

    public function detalleVenta(){
        return $this->hasMany(Detalle_venta::class);

    }

    public function detalleCompra(){
        return $this->hasMany(Detalle_compra::class);

    }
    use HasFactory;
}
