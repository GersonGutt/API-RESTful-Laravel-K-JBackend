<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function lista_pedidos(){
        return $this->belongsTo(Lista_pedido::class);

    }
    public function productos(){
        return $this->belongsTo(Producto::class);

    }
    use HasFactory;
}
