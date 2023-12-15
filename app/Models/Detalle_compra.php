<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_compra extends Model
{
    use HasFactory;

    public function producto(){
        return $this->belongsTo(Producto::class);

    }

    public function compra(){
        return $this->belongsTo(Compra::class);

    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);

    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);

    }

    public function detalleProducto(){
        return $this->belongsTo(Detalle_producto::class);

    }

}
