<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'cantidad',
        'categoria_id',
        'descripcion',
        'estado',
        'id',
        'imagen',
        'nombre',
        'precioTotal',
        'precioUnitario'
    ];

    public function comentarios(){
        return $this->hasMany(Comentario::class);

    }

    public function detalle_compra(){
        return $this->hasMany(Detalle_compra::class);

    }
    public function detalle_venta(){
        return $this->hasMany(Detalle_venta::class);

    }

    public function detalle_producto(){
        return $this->hasMany(Detalle_producto::class);

    }

    public function favoritos(){
        return $this->hasMany(Favorito::class);

    }
    public function lista_pedidos(){
        return $this->hasMany(Lista_pedido::class);

    }
    public function categoria(){
        return $this->belongsTo(Categoria::class);

    }
    public function resenias(){
        return $this->hasMany(Resenia::class);

    }
    public function productos_proveedor(){
        return $this->hasMany(Producto_proveedor::class);

    }

    use HasFactory;
}
