<?php

namespace App\Http\Controllers;
use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    
    public function index()
    {
        try{
            $municipio = Municipio::all();
            return $municipio;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
