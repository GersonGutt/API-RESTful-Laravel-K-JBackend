<?php

namespace App\Http\Controllers;
use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        try{
            $departamentos = Departamento::all();
            return $departamentos;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
