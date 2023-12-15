<?php

namespace App\Http\Controllers;
use App\Models\Detalle_producto;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DetalleProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $deproductos = Detalle_producto:: all();
            $response = $deproductos->toArray();
            $i = 0;
            foreach ($deproductos as $deproducto) {
                $response[$i]["producto"] = $deproducto->producto->toArray();
                $response[$i]["proveedor"] = $deproducto->proveedor->toArray();
                $i++;
            }
            return $deproductos;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    public function getLatest(){
        try{
            $deproductos = Detalle_producto::latest()->take(5)->get();;
            $response = $deproductos->toArray();
            $i = 0;
            foreach ($deproductos as $deproducto) {
                $response[$i]["producto"] = $deproducto->producto->toArray();
                $i++;
            }
            return $deproductos;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $errores = 0;
        $response = [];
        $proveedor = Proveedor::where('id', $request->proveedor['id'])->first();
        $producto = Producto::where('id', $request->producto['id'])->first();
        for($i=0; $i<$request->cantidad; $i++){
            $detail = new Detalle_producto();
            $detail->clave = time() . '_' . Str::random(6);
            $detail->fechaIngreso = Carbon::now()->format('Y/m/d');
            $detail->reservado = 0;
            $detail->estado = 0;
            $detail->producto_id = $request->producto['id'];
            $detail->proveedor_id = $request->proveedor['id'];
            if ($detail->save()<=0){
                $errores++;
            }
            $detail->fechaEgreso = null;
            $detail->proveedor->nombre = $proveedor->nombre;
            $response[] = $detail;
        }
        $producto->cantidad = $producto->cantidad + $request->cantidad;
        if($producto->cantidad <=5){
            $producto->estado = 'L';
        }
        if($producto->cantidad > 5){
            $producto->estado = 'S';
        }
        if ($producto->save()<=0){
            $errores++;
        }
            $data = array(
                'status' => 'ok',
                'code' => '200',
                'message' => 'Se ha agregado el producto',
                'detalle' => $response,
            );

    if ($errores == 0){
        DB::commit();
        return response()->json($data, $data['code']);
    }
    else{
        DB::rollback();
        return response()->json(['status'=>'fail', 'data'=>null], 409);
    }

    }

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
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        $errores = 0;
        try {
            $producto = Producto::where('id', $request->producto_id)->first();
            $detalle = Detalle_producto::findOrFail($request->id);
            $newCantidad = $producto->cantidad - 1;
            $producto->cantidad = $newCantidad;
            if($newCantidad <= 5){
                $producto->estado = 'L';
            }
            if($newCantidad == 0){
                $producto->estado = 'O';
            }
            if ($producto->save()<=0){
                $errores++;
            }
            if ($detalle->delete()<=0){
                $errores++;
            }
            $detalle->producto = $producto;
            if ($errores == 0){
                DB::commit();
                return response()->json($detalle);
            }
            else{
                DB::rollback();
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }

           } catch (\Exception $e) {
            return $e->getMessage();
           }
        }
}
