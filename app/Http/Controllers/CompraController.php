<?php

namespace App\Http\Controllers;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Detalle_producto;
use App\Models\Detalle_compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $compras = Compra:: all();
            $response = $compras->toArray();
             $i = 0;
             foreach ($compras as $compra) {
              $detalle = $compra->detalle_compra->toArray();
              $f=0;
              foreach($compra->detalle_compra as $d) {
                $detalle[$f]['producto'] = $d->producto->toArray();
                $detalle[$f]['proveedor'] = $d->proveedor->toArray();
                $detalle[$f]['detalle'] = $d->detalleProducto->toArray();
              $f++;
              }
              $response[$i]['detalleCompra'] = $detalle;

              $i++;
             }
            return $response;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    public function ComprasAll(){
        // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
        try{
            $compras = Compra::where('estado', 'A')->get();
        $response = $compras->toArray();
        $compra = json_decode($compras, true);
         $i = 0;
         foreach ($compras as $compra) {
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($compra->detalle_Compra as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
             $detalle[$f]['producto']['proveedor'] = $d->proveedor->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
            $response[$i]['nombreProveedor'] = $detalle[$f]['producto']['proveedor']['nombre'];
            $response[$i]['empresa'] = $detalle[$f]['producto']['proveedor']['empresa'];
            $response[$i]['descuento'] = $detalle[$f]['producto']['proveedor']['descuento'];
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleCompra'][$n] = $detalle[$n]['producto'];
          }
          $detalle = [];
          $i++;
         }
         return $response;
        }catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
     }

     public function ComprasAllInactivas(){
        // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
        try{
            $compras = Compra::where('estado', 'I')->get();
        $response = $compras->toArray();
        $compra = json_decode($compras, true);
         $i = 0;
         foreach ($compras as $compra) {
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($compra->detalle_Compra as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
             $detalle[$f]['producto']['proveedor'] = $d->proveedor->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
            $response[$i]['nombreProveedor'] = $detalle[$f]['producto']['proveedor']['nombre'];
            $response[$i]['empresa'] = $detalle[$f]['producto']['proveedor']['empresa'];
            $response[$i]['descuento'] = $detalle[$f]['producto']['proveedor']['descuento'];
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleCompra'][$n] = $detalle[$n]['producto'];
          }
          $detalle = [];
          $i++;
         }
         return $response;
        }catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
     }

     public function ChangeState(Request $request){
        $venta = Compra::findOrFail($request->id);
        $venta->estado = $request->estado;
        if ($venta->save()<=0){
            return response()->json(['status'=>'fail', 'data'=>null], 409);
        }else{
            return response()->json(['status'=>'ok', 'data'=>null], 200);
        }
}

public function Reporte(Request $request){
    $fechaInicio = $request["fechaInicio"];
    $fechaFinal = $request["fechaFinal"];
    $compras = Compra::where('estado', 'A')->get();
    $response = $compras->toArray();
    $compra = json_decode($compras, true);
     $i = 0;
     $total = 0;
     foreach ($compras as $compra) {
      $detail = [];
      $exist = 0;
      $n = 0;
      foreach($compra->detalle_Compra as $d) {
         $f= $d->producto_id;
         if(isset($detalle[$f])){
             $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
         }
         else{
         $detalle[$f]['producto'] = $d->producto->toArray();
         $detalle[$f]['producto']['proveedor'] = $d->proveedor->toArray();
        $detalle[$f]['producto']['cantidad'] = 1;
        $response[$i]['nombreProveedor'] = $detalle[$f]['producto']['proveedor']['nombre'];
        $response[$i]['empresa'] = $detalle[$f]['producto']['proveedor']['empresa'];
        $response[$i]['descuento'] = $detalle[$f]['producto']['proveedor']['descuento'];
         }
      }
      foreach($detalle as $detail){
        $n = $detail['producto']['id'];
        $response[$i]['detalleCompra'][$n] = $detalle[$n]['producto'];
      }
      $total = $total + $compra->total;
      $detalle = [];
      $i++;
     }
     $compras = $response;
     $fechaReporte = Carbon::now()->format('Y/m/d');
     $pdf = Pdf::loadView('reports.reportecompras', compact('compras', 'fechaReporte', 'total', 'fechaInicio', 'fechaFinal'));
     $pdf->setPaper('landscape');

     return $pdf->stream('DetailCompras.pdf');
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
        try{
            $errores = 0;
            $asd = 0;
            DB::beginTransaction();
            $latestVenta = Compra::latest('id')->first();
            $numeroFactura = $latestVenta->numerofactura + 1;
            $venta = new Compra();
            $venta->fechaCompra = Carbon::now()->format('Y/m/d');
            $venta->total = $request->total;
            $venta->estado = 'A';
            $venta->numerofactura = $numeroFactura;
            $venta->observaciones = $request->observaciones;
            if ($venta->save()<=0){
                $errores++;
            }
            //obtener el detalle del provider para luego insertar en detalle_provideres
            $productos = $request->productos;
            foreach ($productos as $key => $det){
                $product = Producto::where('id', $det['id'])->first();
                $cantidad = $det['cantidad'] + $product->cantidad;
                $product->cantidad = $cantidad;
                if($cantidad > 0){
                    $product->estado = 'L';
                }
                if($cantidad > 5){
                    $product->estado = 'S';
                }
                $product->precioTotal = $product->precioUnitario * $cantidad;
                    if($product->save()<=0){
                        $errores++;
                    }
                for ($x = 0; $x < $det['cantidad']; $x++ ){
                    $detail = new Detalle_producto();
                    $detail->clave = time() . '_' . Str::random(6);
                    $detail->fechaIngreso = Carbon::now()->format('Y/m/d');
                    $detail->reservado = 0;
                    $detail->estado = 0;
                    $detail->producto_id = $det['id'];
                    $detail->proveedor_id = $det['proveedor']['id'];
                    if ($detail->save()<=0){
                        $errores++;
                    }
                    $detalle_compra = new Detalle_compra();
                    $detalle_compra->compra_id = $venta->id;
                    $detalle_compra->producto_id =$det['id'];
                    $detalle_compra->proveedor_id = $det['proveedor']['id'];
                    $detalle_compra->detalle_producto_id = $detail->id;
                    if ($detalle_compra->save()<=0){
                        $errores++;
                    }
                    $detail->fechaEgreso = null;
                    $response[] = $detail;
                }
            }

            if ($errores == 0){
                DB::commit();
                return response()->json(['status'=>'ok', 'data'=>$venta, 'message'=>'Venta realizada con exito'], 200);
            }else{
                DB::rollback();
                return response()->json($errors);
            }
        }catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
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
    public function destroy(string $id)
    {
        //
    }
}
