<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_Favorito extends Model
{
    public function favoritos(){
        return $this->hasMany(Favorito::class);

    }
    public function users(){
        return $this->belongsTo(User::class);

    }
    use HasFactory;
}
