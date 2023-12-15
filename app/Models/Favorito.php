<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{

    public function lista_favoritos(){
        return $this->belongsTo(Lista_Favorito::class);

    }
    public function productos(){
        return $this->belongsTo(Producto::class);

    }
    use HasFactory;
}
