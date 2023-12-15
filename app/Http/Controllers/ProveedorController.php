<?php

namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\Producto_proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $proveedores = Proveedor::where('estado', '=', '1')->get();
            $response = $proveedores->toArray();
            $i=0;
            foreach($proveedores as $proveedor){
                $f=0;
                $detalle = [];
                foreach ($proveedor->productos_proveedor as $productos){
                    if($productos->producto->estado != 'I'){
                        $detalle[$f] = $productos->producto->toArray();
                        $f++;
                        $n=0;
                        foreach($proveedor->productos_proveedor as $d){
                            $detalle[$n]['categoria'] = $d->producto->categoria->toArray();
                        }
                    }

                  }
                  $response[$i]['productos'] = $detalle;
                  $i++;
                    }

            return $response;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function showInactivos()
    {
        try{
            $proveedores = Proveedor::where('estado', '=', '0')->get();
            $response = $proveedores->toArray();

            $i=0;
            foreach($proveedores as $proveedor){
                $f=0;
                $detalle = [];
                foreach ($proveedor->productos_proveedor as $productos){
                    $detalle[$f] = $productos->producto->toArray();
                    $f++;
                }
                $response[$i]['productos'] = $detalle;
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

    public function getProviders()
    {
        try{
            $proveedores = Proveedor::where('estado', '=', '1')->get();
            return $proveedores;
        }catch(\Exception $e){
            return $e->getMessage();
        }
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
            $array['nombre'] = $request->nombre;
            $array['telefono'] =  $request->telefono;
            $array['direccion'] = $request->direccion;
            $array['empresa'] = $request->empresa;
            $array['descuento'] = $request->descuento;
            $validate = Validator::make($array, [
                'nombre' => 'required|string',
                'telefono' => 'required|regex:/^\d{4}-\d{4}$/',
                'direccion' => 'required',
                'empresa' => 'required',
                'descuento' => 'required|regex:/^[0-9]+$/',
            ],
            [
                'nombre.string' => 'El "Nombre" no debe contener numeros',
                'nombre.required' => 'El campo "Nombre" no debe quedar vacio',
                'telefono.regex' => 'formato invalido: (Ej: 1111-1111)',
                'telefono.required' => 'El campo "Telefono" no puede quedar vacio.',
                'direccion.required' => 'El campo "Direccion" no puede quedar vacio.',
                'empresa.required' => 'El campo "Empresa" no puede quedar vacio.',
                'descuento.required' => 'El campo "Descuento" no puede quedar vacio.',
                'descuento.regex' => '"Descuento" no puede ser negativo.',
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

            $provider = new Proveedor();
            $provider->nombre = $request->nombre;
            $provider->telefono = $request->telefono;
            $provider->direccion = $request->direccion;
            $provider->empresa = $request->empresa;
            $provider->descuento = $request->descuento;
            if ($provider->save()<=0){
                $errores++;
            }
            //obtener el detalle del provider para luego insertar en detalle_provideres
            $detalle = $request->productos;
            foreach ($detalle as $key => $det){
               $productoProveedor = new Producto_proveedor();
               $productoProveedor->producto_id = $det['id'];
               $productoProveedor->proveedor_id = $provider->id;
               if($productoProveedor->save()<=0){
                $errores++;
               }
            }

            if ($errores == 0){
                DB::commit();
                $provider->productos = $detalle;
                return response()->json(['status'=>'ok', 'data'=>$provider], 200);
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
    public function update(Request $request)
    {

        try{
            $errores = 0;
            DB::beginTransaction();

            $array = array();
            $array['nombre'] = $request->nombre;
            $array['telefono'] =  $request->telefono;
            $array['direccion'] = $request->direccion;
            $array['empresa'] = $request->empresa;
            $array['descuento'] = $request->descuento;
            $validate = Validator::make($array, [
                'nombre' => 'required|string',
                'telefono' => 'required|regex:/^\d{4}-\d{4}$/',
                'direccion' => 'required',
                'empresa' => 'required',
                'descuento' => 'required|regex:/^[0-9]+$/',
            ],
            [
                'nombre.string' => 'El "Nombre" no debe contener numeros',
                'nombre.required' => 'El campo "Nombre" no debe quedar vacio',
                'telefono.regex' => 'formato invalido: (Ej: 1111-1111)',
                'telefono.required' => 'El campo "Telefono" no puede quedar vacio.',
                'direccion.required' => 'El campo "Direccion" no puede quedar vacio.',
                'empresa.required' => 'El campo "Empresa" no puede quedar vacio.',
                'descuento.required' => 'El campo "Descuento" no puede quedar vacio.',
                'descuento.regex' => '"Descuento" no puede ser negativo.',
            ]);

            if($validate->fails()) {
                $errors = array(
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'No es posible Actualizar el proveedor',
                    'errors' => $validate->errors()
                );
                return response()->json($errors);
            }

            $provider = Proveedor::findOrFail($request->id);
            $provider->nombre = $request->nombre;
            $provider->telefono = $request->telefono;
            $provider->direccion = $request->direccion;
            $provider->empresa = $request->empresa;
            $provider->descuento = $request->descuento;
            if ($provider->update()<=0){
                $errores++;
            }
               //obtener el detalle del provider para luego insertar en detalle_provideres
                        $productoProvider = $request->productos;
                        $entro = 0;
                        $verifico = 0;
                        $intentoeliminar = 0;
                        $productoProveedor = Producto_proveedor::where('proveedor_id', '=', $provider->id)->get();
                        if(Producto_proveedor::where('proveedor_id', '=', $provider->id)->first() == null){
                            foreach ($productoProvider as $key => $producto){
                                $productProvider = new Producto_proveedor();
                                $productProvider->producto_id = $producto['id'];
                                $productProvider->proveedor_id = $provider->id;
                                if($productProvider->save()<=0){
                                 $errores++;
                                }
                            }
                        }
                        foreach ($productoProveedor as $key => $productoBase)
                                {
                        foreach ($productoProvider as $key => $producto){
                                    if ($producto['id'] == $productoBase->producto_id){
                                            if(array_key_exists('action', $producto)){
                                                $intentoeliminar++;
                                                $productoDelete = Producto_proveedor::find($productoBase->id);
                                                if($productoDelete->delete()<=0){
                                                    $errores++;
                                                }
                                            }
                                    }
                                    else{
                                        if(Producto_proveedor::where('producto_id', $producto['id'])->where('proveedor_id', $request->id)
                                        ->first())
                                        {
                                            $verifico++;
                                        }
                                        else{
                                            if(array_key_exists('action', $producto)){

                                            $productoProveedor = Producto_proveedor::updateOrCreate(
                                                ['proveedor_id' => $provider->id, 'producto_id' => $producto['id']]
                                            );
                                    }
                                        }
                                    }
                                }

                                }

            if ($errores == 0){
                $provider->productos = $productoProveedor;
                DB::commit();
                return response()->json(['status'=>'ok', 'data'=>$provider, 'elimino'=>$intentoeliminar], 200);

            }else{
                DB::rollback();
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }
        }catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function ChangeState(Request $request){
            $provider = Proveedor::findOrFail($request->id);
            $provider->estado = $request->estado;
            if ($provider->save()<=0){
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }else{
                return response()->json(['status'=>'ok', 'data'=>$provider], 200);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
