<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    public function direccion(){
        return $this->belongsTo(Direccion::class);

    }
    public function productos_proveedor(){
        return $this->hasMany(Producto_proveedor::class);

    }

    public function detalle_producto(){
        return $this->hasMany(Detalle_producto::class);
    }

    public function detalleCompra(){
        return $this->hasMany(Detalle_producto::class);
    }
    use HasFactory;
}
