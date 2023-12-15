<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_pedido extends Model
{
    public function users(){
        return $this->belongsTo(User::class);

    }
    public function lista_pedidos(){
        return $this->hasMany(Lista_pedido::class);

    }
    use HasFactory;
}
