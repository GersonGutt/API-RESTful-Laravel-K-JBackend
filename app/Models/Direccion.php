<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direccion';
    public function municipio(){
        return $this->belongsTo(Municipio::class);

    }

    public function departamento(){
        return $this->belongsTo(Departamento::class);

    }

    public function proveedores(){
        return $this->hasMany(Proveedor::class);

    }

    use HasFactory;
}
