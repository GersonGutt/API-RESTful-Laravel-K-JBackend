<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto_proveedor extends Model
{
    use HasFactory;
    protected $table = 'productos_proveedor';

    protected $fillable = ['producto_id', 'proveedor_id'];

    public function producto(){
        return $this->belongsTo(Producto::class);

    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);

    }

}
