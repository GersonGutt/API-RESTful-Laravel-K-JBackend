<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resenia extends Model
{
    public function productos(){
        return $this->belongsTo(Producto::class);

    }
    public function users(){
        return $this->belongsTo(User::class);

    }
    use HasFactory;
}
