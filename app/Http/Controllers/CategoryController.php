<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categorias = Categoria:: all();
            return $categorias;

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
        // $params_array = array();
        // $params_array['nombre'] = $request->nombre;

        // if( $params_array['nombre'] != ''){

        //     $params_array =  array_map('trim', $params_array);
        //     $validate = Validator::make($params_array, [
        //         'nombre' => ['required', 'alpha', 'regex:/^[^\d!@#$%^&*()_+{}|:"<>?~`]+$/']

        //     ]);
        //     if($validate->fails()) {
        //         $data = array(
        //             'status' => 'error',
        //             'code' => '404',
        //             'message' => 'El categoria no se ha creado',

        //         );
        //         return response()->json($data, $data['code']);
        //     }else{
        //         $categoria = new Categoria();
        //     $categoria->nombre =$params_array['nombre'];
        //     $categoria->save();

        //         $data = array(
        //             'status' => 'success',
        //             'code' => '200',
        //             'message' => 'El categoria registrado correctamente',
        //       );
        //     }



        // }else{
        //     $data = array(
        //         'status' => 'error',
        //         'code' => '404',
        //         'message' => 'datos enviados no son correctos',
        //     );
        // }
        // return response()->json($data, $data['code']);
        try{

            $nombreVal = 'regex:/^[^\d!@#$%^&*()_+{}|:"<>?~`]+$/';
            $validator = Validator::make($request->all(), [
                'nombre' => [$nombreVal, 'required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'message' => 'Categoria solo puede ser cadena de texto'], 409);
            }
            $categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            if ($categoria->save()) {
                return response()->json(['status'=>'ok', 'data'=>$categoria], 201);
            } else {
                return response()->json(['status'=>'fail', 'message' => 'Error en el sistema'], 409);
            }


        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $categoria = Categoria::findOrFail($id);
            return $categoria;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        try {
            $nombreVal = 'regex:/^[^\d!@#$%^&*()_+{}|:"<>?~`]+$/';
            $validator = Validator::make($request->all(), [
                'nombre' => [$nombreVal, 'required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'fail', 'message' => 'Categoria solo puede ser cadena de texto'], 409);
            }
            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = $request->nombre;
            if ($categoria->update()>=1) {
                return response()->json(['status'=>'ok', 'data'=>$categoria], 202);

            } else {
                return response()->json(['status'=>'fail', 'data'=>null], 409);
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       try {
        $categoria = Categoria::findOrFail($request->id);
        if ($categoria->delete()) {
            return response()->json(['status'=>'ok', 'data'=>'Categoria eliminada'], 204);
        }
        else {
            return response()->json(['status' => 'error', 'message' => 'No se pudo eliminar la categoría'], 500);
        }
       } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
       }
    }
}
