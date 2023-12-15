<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{

    public function direccion(){
        return $this->hasMany(Direccion::class);

    }

    use HasFactory;
}
