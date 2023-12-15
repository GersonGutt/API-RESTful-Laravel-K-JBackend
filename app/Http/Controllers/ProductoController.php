<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Producto_proveedor;
use App\Models\Detalle_producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $productos = Producto::where('estado', '!=', 'I')->orderBy('nombre')->get();
            $response = $productos->toArray();
            $i = 0;
            foreach ($productos as $producto) {
                $response[$i]["categoria"] = $producto->categoria->toArray();
                $detalle = $producto->detalle_producto->toArray();
                $f = 0;
                foreach($producto->detalle_producto as $detail){
                    $detalle[$f]['proveedor'] = $detail->proveedor->toArray();
                    $f++;
                }
                $response[$i]['detalleProductos'] = $detalle;
                $i++;
            }
            return $productos;
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    public function productosReporte()
    {
        try{
            $productos = Producto::where('estado', '!=', 'I')->orderBy('nombre')->get();
            $response = $productos->toArray();
            $i = 0;
            $total = 0;
            foreach ($productos as $producto) {
                $response[$i]["categoria"] = $producto->categoria->toArray();
                $total = $total + $producto->precioTotal;
            }
            $data = $productos;
            $fechaReporte = Carbon::now()->format('Y/m/d');
        $pdf = Pdf::loadView('reports.productos', compact('data', 'fechaReporte', 'total'));
        return $pdf->stream('Factura.pdf');
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

    public function productosReportefindemes()
    {
        try{
            $inicio = date("Y-m-01");
            $fin = date("Y-m-t");
            $productos = Producto::where('estado', '!=', 'I')->orderBy('nombre')->get();
            $response = $productos->toArray();
            $i = 0;
            $total = 0;
            foreach ($productos as $producto) {
                $response[$i]["categoria"] = $producto->categoria->toArray();
                $total = $total + $producto->precioTotal;
            }
            $data = $productos;
            $fechaReporte = Carbon::now()->format('Y/m/d');
        $pdf = Pdf::loadView('reports.productos', compact('data', 'fechaReporte', 'total'));
         $pdfContent = $pdf->output();
         $nombreArchivo = time() . '_' . 'reporteProductos';
         $nombreArchivo = $nombreArchivo . '.pdf';
         Storage::disk('pdf')->put($nombreArchivo, $pdfContent);
        }catch(\Exception $e){
             return $e->getMessage();
        }
    }

   public function getDetailWithProducts()
{
    try {
        $productos = Producto::where('estado', '!=', 'I')->orderBy('nombre')->get();

        $response = $productos->toArray();
        $i = 0;

        foreach ($productos as $producto) {
            $response[$i]["categoria"] = $producto->categoria->toArray();
            $f = 0;
            $detalle = []; // Inicializa un arreglo para almacenar los detalles con estado "0".

            foreach ($producto->detalle_producto as $detail) {
                if ($detail->estado == 0) {
                    $detalle[$f] = $detail->toArray();
                    $detalle[$f]['proveedor'] = $detail->proveedor->toArray();
                    $detalle[$f]['producto'] = $detail->producto->toArray();
                    $f++;
                }
            }

            $response[$i]['detalle_producto'] = $detalle;
            $i++;
        }

        return $response; // Devuelve la respuesta modificada.
    } catch (\Exception $e) {
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
        //
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
    public function createOrUpdate(Request $request)
    {
        DB::beginTransaction();
        $errores = 0;
        $productoJSON = $request->file('producto')->getContent();
        $producto = json_decode($productoJSON, true);
        $productoReturn = json_decode($productoJSON, true);
       //TODO:: VALIDAR
       $validate = Validator::make($producto, [
           'nombre' => 'required|regex:/^[a-zA-Z\s]{2,60}$/',
           'descripcion' => 'required',
           'precioTotal' => 'regex:/^\d+(.\d+)?$/',
           'precioUnitario' => 'required|regex:/^(?!0$)\d+(.\d+)?$/',
           'cantidad' => 'required|regex:/^(?!0$)[0-9]+$/',
       ],
       [
           'nombre.regex' => 'El "Nombre" no debe contener numeros',
           'nombre.required' => 'El campo "Nombre" no debe quedar vacio',
           'descripcion.required' => 'El campo "Descripcion" no debe quedar vacio.',
           'precioUnitario.required' => 'llene el campo.',
           'precioUnitario.regex' => 'ingrese un numero positivo.',
           'cantidad.required' => 'llene el campo.',
           'cantidad.regex' => 'ingrese un numero positivo.',
       ]);

       if($validate->fails()) {
           $errors = array(
               'status' => 'error',
               'code' => '404',
               'message' => 'No es posible agregar al Proveedor',
               'errors' => $validate->errors()
           );
           return response()->json($errors);
       }

       //TODO:: END VALIDAR
        $producto['categoria_id'] = $producto['categoria']['id'];
        unset($producto['categoria']);
        unset($producto['show']);
        unset($producto['action']);
        unset($producto['proveedor']);
        unset($producto['detalle_producto']);
        unset($producto['created_at']);
        unset($producto['updated_at']);
        if(Producto::where('nombre', $producto['nombre'])->where('id', '!=', $producto['id'])->first()){
            $data = array(
                'status' => 'error',
                'code' => '404',
                'message' => 'El Producto: "' . $producto['nombre'] . '" ya existe en la base de datos',
            );
        }
        else{
            if ($request->hasFile('imagen')) {
                //Verifica si la imagen ya existe en el backend
                if(Storage::disk('public')->exists($producto['imagen'])){
                    //borrar la imagen ya existente
                   Storage::disk('public')->delete($producto['imagen']);
                }
                //Le damos un nuevo nombre a la imagen para que no se repitan en el backend
                //y guardamos la imagen en la carpeta public del backend
                $imagen = $request->file('imagen');
                $nombreArchivo = time() . '_' . $imagen->getClientOriginalName();
                $producto['imagen'] = $nombreArchivo;
                $productoReturn['imagen'] = $nombreArchivo;
                Storage::disk('public')->put($nombreArchivo, file_get_contents($imagen));
            }
            else{
                //no trae imagen
                if($productoReturn['action'] != 'update'){
                    $imagen = 'null';
                    $producto['imagen'] = $imagen;
                    $productoReturn['imagen'] = $imagen;
                }
            }
            if($productoReturn['action'] == 'update'){
                if(Producto::where('id', $producto['id'])->update($producto)){
                    $data = array(
                        'status' => 'ok',
                        'code' => '200',
                        'message' => 'El Producto: "' . $producto['nombre'] . '" ha sido actualizado',
                        'producto' => $productoReturn,
                    );
                }
            }
            else{
                if($newProduct = Producto::create($producto)){
                    for($i=0; $i<$producto['cantidad']; $i++){
                    $provider = Proveedor::findOrFail($productoReturn['proveedor']['id']);
                    $detail = new Detalle_producto();
                    $detail->clave = time() . '_' . Str::random(6);
                    $detail->fechaIngreso = Carbon::now()->format('Y/m/d');
                    $detail->fechaEgreso = null;
                    $detail->reservado = 0;
                    $detail->estado = 0;
                    $detail->producto_id = $newProduct->id;
                    $detail->proveedor_id = $productoReturn['proveedor']['id'];
                    if ($detail->save()<=0){
                        $errores++;
                    }
                    $detail['proveedor'] = $provider;
                    $productoReturn['detalle_producto'][$i] = $detail;
                    }
                    $productProvider = new Producto_proveedor();
                    $productProvider->producto_id = $newProduct->id;
                    $productProvider->proveedor_id = $productoReturn['proveedor']['id'];
                    if ($productProvider->save()<=0){
                        $errores++;
                    }
                    $data = array(
                        'status' => 'ok',
                        'code' => '200',
                        'message' => 'Se ha agregado el producto: "' . $producto['nombre'],
                        'producto' => $productoReturn,
                    );
                }
            }
        }
        if ($errores == 0){
            DB::commit();
            return response()->json($data, $data['code']);
        }
        else{
            DB::rollback();
            return response()->json(['status'=>'fail', 'data'=>null], 409);
        }



    }

    public function deactivate(Request $request){
        $product = $request->all();
        $producto = Producto::find($product['id']);
        $producto->estado = 'I';
        $producto->nombre = $producto->nombre . '_deleted';

        if(Producto::where('id', $producto['id'])){
            if($producto->save()){
                return response()->json(['status'=>'success', 'data'=>$producto], 200);
            }else{
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        $errores = 0;

        if ($errores == 0){
            DB::commit();
            return response()->json($data, $data['code']);
        }

    }
}
