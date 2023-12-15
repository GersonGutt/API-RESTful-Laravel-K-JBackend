<?php

namespace App\Http\Controllers;
use App\Models\Detalle_producto;
use App\Models\Producto_proveedor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $providers = Producto_proveedor::all()->where('proveedor_id', $request->id);
            $response = $providers->toArray();
            $i = 0;
            foreach ($providers as $provider) {
                $response[$i]["producto"] = $provider->producto->toArray();
                $i++;
            }
            return $providers;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getByProductId(Request $request)
{
    try {
        $providers = Producto_proveedor::where('producto_id', $request->id)->get();
        $response = [];

        foreach ($providers as $provider) {
            $proveedorData = $provider->proveedor->toArray();
            $response[] = $proveedorData;
        }

        return response()->json($response);
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
