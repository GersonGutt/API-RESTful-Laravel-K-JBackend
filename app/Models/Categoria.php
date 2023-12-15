<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function detalle_compras(){
        return $this->hasMany(Detalle_compra::class);

    }
    public function producto(){
        return $this->hasMany(Producto::class);

    }
    use HasFactory;
}
