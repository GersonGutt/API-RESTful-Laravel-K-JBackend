<?php

namespace App\Http\Controllers;
use App\Models\Venta;
use App\Models\Detalle_venta;
use App\Models\Producto;
use App\Models\Detalle_producto;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $ventas = Venta:: all();
            $response = $ventas->toArray();
             $i = 0;
             foreach ($ventas as $venta) {
              $detalle = $venta->detalle_venta->toArray();
              $f=0;
              foreach($venta->detalle_venta as $d) {
                $detalle[$f]['producto'] = $d->producto->toArray();
                $detalle[$f]['producto']['categoria'] = $d->producto->categoria->toArray();
                $detalle[$f]['detalle'] = $d->detalleProducto->toArray();
              $f++;
              }
              $response[$i]['detalleVenta'] = $detalle;

              $i++;
             }
            return $response;
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
        try{
            $errores = 0;
            DB::beginTransaction();
            $array = array();
            $array['cliente'] = $request->cliente;
            $array['dui'] =  $request->dui;
            $array['direccion'] = $request->direccion;
            $validate = Validator::make($array, [
                'cliente' => 'required|string',
                'dui' => 'required|regex:/^\d{8}-[0-9]$/',
                'direccion' => 'required'
            ],
            [
                'cliente.string' => 'El "Cliente" no debe contener numeros',
                'cliente.required' => 'El campo "Cliente" no debe quedar vacio',
                'dui.regex' => 'formato invalido: (Ej: 12345678-9)',
                'dui.required' => 'El campo "Dui" no debe quedar vacio.',
                'direccion.required' => '"Direccion" no debe quedar vacio.',
            ]);

            if($validate->fails()) {
                $errors = array(
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'No es posible realizar la Venta',
                    'errors' => $validate->errors()
                );
                return response()->json($errors);
            }
            $latestVenta = Venta::latest('id')->first();
            $numeroFactura = $latestVenta->numerofactura + 1;

            $venta = new Venta();
            $venta->cliente = $request->cliente;
            $venta->dui = $request->dui;
            $venta->direccion = $request->direccion;
            $venta->fechaVenta = Carbon::now()->format('Y/m/d');
            $venta->total = $request->total;
            $venta->estado = 'A';
            $venta->numerofactura = $numeroFactura;
            if ($venta->save()<=0){
                $errores++;
            }
            $venta->gmail = $request->gmail;
            //obtener el detalle del provider para luego insertar en detalle_provideres
            $productos = $request->productos;
            foreach ($productos as $key => $det){
                $product = Producto::where('id', $det['id'])->first();
                $cantidad = $det['cantidad'];
                $product->cantidad = $cantidad;
                $product->precioTotal = $product->precioUnitario * $cantidad;
                if($det['estado'] == 'S' && $cantidad <=5){
                    $notificacion = new Notificacion();
                    $notificacion->notificacion = 'Quedan pocas existencias del producto: ' . $product->nombre;
                    $notificacion->is_readed = 0;
                    $notificacion->user_id = 1;
                    $product->estado = 'L';
                }
                if($det['estado'] == 'L' && $cantidad ==0 || $det['estado'] == 'S' && $cantidad ==0){
                    $notificacion = new Notificacion();
                    $notificacion->notificacion = 'Se agotaron las existencias del producto: ' . $product->nombre;
                    $notificacion->is_readed = 0;
                    $notificacion->user_id = 1;
                    $product->estado = 'O';
                }
                if(isset($notificacion)){
                    if($notificacion->save()<=0){
                        $errores++;
                    }
                }
                    if($product->save()<=0){
                        $errores++;
                    }
                //TODO:: CAMBIAR ESTADO Y ENVIAR NOTIFICACION
                foreach ($det['detalle_producto'] as $key => $detail){
                    $detalle = Detalle_producto::find($detail['id']);
                    $detalle->estado = 1;
                    $detalle->fechaEgreso = Carbon::now()->format('Y/m/d');
                    $producto = new Detalle_venta();
                    $producto->producto_id = $detail['producto_id'];
                    $producto->venta_id = $venta->id;
                    $producto->detalle_producto_id = $detail['id'];
                    if($producto->save()<=0){
                     $errores++;
                    }

                    if($detalle->save()<=0){
                    $errores++;
                    }
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

    public function factura(Request $request){
       // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
       $ventas = Venta::where('id', $request->id)->get();
       $response = $ventas->toArray();
       $venta = json_decode($ventas, true);
        $i = 0;
        foreach ($ventas as $venta) {
         $productos = [];
         $exist = 0;
         foreach($venta->detalle_venta as $d) {
            $f= $d->producto_id;
            if(isset($detalle[$f])){
                $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
            }
            else{
            $detalle[$f]['producto'] = $d->producto->toArray();
           $detalle[$f]['producto']['cantidad'] = 1;
            }

         }

         $response['detalleVenta'] = $detalle;
         $i++;
        }
        $factura = $response;
        $pdf = Pdf::loadView('reports.newfactura', compact('factura'));
        return $pdf->stream('Factura.pdf');
    }

    public function facturacorreo(Request $request){
        // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
        $ventas = Venta::where('id', $request->id)->get();
        $response = $ventas->toArray();
        $venta = json_decode($ventas, true);
         $i = 0;
         foreach ($ventas as $venta) {
          $productos = [];
          $exist = 0;
          foreach($venta->detalle_venta as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
             }

          }

          $response['detalleVenta'] = $detalle;
          $i++;
         }
         $factura = $response;
         $pdf = Pdf::loadView('reports.newfactura', compact('factura'));
         $pdfContent = $pdf->output();
         $nombreArchivo = time() . '_' . 'pdf';
         $nombreArchivo = $nombreArchivo . '.pdf';
         Storage::disk('pdf')->put($nombreArchivo, $pdfContent);
         app()->call('App\Http\Controllers\SendMailController@sendfactura', ['email' => $request->gmail, 'nombre' => $nombreArchivo]);
         return $pdf->stream('Factura.pdf');
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

    public function VentasAll(){
        // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
        try{
            $ventas = Venta::where('estado', 'A')->get();
        $response = $ventas->toArray();
        $venta = json_decode($ventas, true);
         $i = 0;
         foreach ($ventas as $venta) {
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($venta->detalle_venta as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleVenta'][$n] = $detalle[$n]['producto'];
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

     public function VentasInactivas(){
        // $venta = Venta::with('detalle_venta.producto')->where('id', '31')->first();
        try{
            $ventas = Venta::where('estado', 'I')->get();
        $response = $ventas->toArray();
        $venta = json_decode($ventas, true);
         $i = 0;
         foreach ($ventas as $venta) {
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($venta->detalle_venta as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleVenta'][$n] = $detalle[$n]['producto'];
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

     public function Reporte(Request $request){
        $fechaInicio = $request["fechaInicio"];
        $fechaFinal = $request["fechaFinal"];
        $ventas = Venta::whereBetween('fechaVenta', [$fechaInicio,$fechaFinal])->where('estado', 'A')->get();
       // $ventas = Venta::all();
        $response = $ventas->toArray();
        $venta = json_decode($ventas, true);
         $i = 0;
         $total = 0;
         foreach ($ventas as $venta) {
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($venta->detalle_venta as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleVenta'][$n] = $detalle[$n]['producto'];
          }
          $total = $total + $venta->total;
          $detalle = [];
          $i++;
         }
         $ventas = $response;
         $fechaReporte = Carbon::now()->format('Y/m/d');
         $pdf = Pdf::loadView('reports.reporteventas', compact('ventas', 'fechaReporte', 'total', 'fechaInicio', 'fechaFinal'));
         $pdf->setPaper('landscape');

         return $pdf->stream('DetailVentas.pdf');
     }

     public function gananciasMes(){
        $primerDia = Carbon::now()->startOfMonth();
        $ultimoDia = Carbon::now()->endOfMonth();
        $ventas = Venta::whereBetween('fechaVenta', [$primerDia,$ultimoDia])->where('estado', 'A')->get();
       // $ventas = Venta::all();
        $response = $ventas->toArray();
        $venta = json_decode($ventas, true);
         $i = 0;
         $total = 0;
         $cantidadTotal = 0;
         $clientes = 0;
         foreach ($ventas as $venta) {
            $clientes = $clientes + 1;
          $detail = [];
          $exist = 0;
          $n = 0;
          foreach($venta->detalle_venta as $d) {
             $f= $d->producto_id;
             if(isset($detalle[$f])){
                 $detalle[$f]['producto']['cantidad'] = $detalle[$f]['producto']['cantidad'] + 1;
                 $cantidadTotal = $cantidadTotal + 1;
             }
             else{
             $detalle[$f]['producto'] = $d->producto->toArray();
            $detalle[$f]['producto']['cantidad'] = 1;
            $cantidadTotal = $cantidadTotal + 1;
             }
          }
          foreach($detalle as $detail){
            $n = $detail['producto']['id'];
            $response[$i]['detalleVenta'][$n] = $detalle[$n]['producto'];
          }
          $total = $total + $venta->total;
          $response['total'] = $total;
          $response['cantidad'] = $cantidadTotal;
          $response['clientes'] = $clientes;
          $i++;
         }
         return $response;
     }



     public function ChangeState(Request $request){
        $venta = Venta::findOrFail($request->id);
        $venta->estado = $request->estado;
        if ($venta->save()<=0){
            return response()->json(['status'=>'fail', 'data'=>null], 409);
        }else{
            return response()->json(['status'=>'ok', 'data'=>null], 200);
        }
}

}
