<?php

namespace App\Http\Controllers;

use App\helpers\JwtAuth;
use App\Models\User;
use App\Http\Controllers\SendMailController;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           try{
            $users = User::all();
            return $users;
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
        //
    }

    public function login(Request $request)
    {
       $jwtAuth = new JwtAuth;

        //recibir datos por POST
        $json = $request;
        $array = array();
        $array['email'] = $request->email;
        $array['password'] = $request->password;

        //validar esos datos
        $validate = Validator::make($array, [
            'email' => 'required|email',
            'password' => 'required|regex:/^[\w-]*$/',
        ],
        [
            'email.required' => 'El campo "Email" no puede quedar vacio.',
            'email.email' => 'Formato de correo incorrecto. ej: "Example@domain.com"',
            'password.regex' => 'Caracteres invalidos: (Ej: !, $, #, o %)',
            'password.required' => 'El campo "Contraseña" no puede quedar vacio.',
        ]);

        if($validate->fails()) {
            $singup = array(
                'status' => 'error',
                'code' => '404',
                'message' => 'El usuario no se ha podido logear',
                'errors' => $validate->errors()
            );

        }else{
             //cifrar la contraseña
            $pwd = hash('sha256', $request->password);
             //devolver token o datos
            $singup = $jwtAuth->singup($request->email, $pwd);
            if(!empty($request->getToken)){
                $singup = $jwtAuth->singup($request->email, $pwd, true);
            }
        }

       return response()->json(($singup), 200);
    }

    public function register(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params) && !empty($params_array)){
            //limpiar datos(trim)
        $params_array =  array_map('trim', $params_array);

        //validando con validator
        $validate = \Validator::make($params_array, [
            'name' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => '404',
                'message' => 'El usuario no se ha creado',
                'errors' => $validate->errors()
            );

        }else{
           $pwd = hash('sha256', $params->password);
            $user = new User();
            $user->name = $params_array['name'];
            $user->email = $params_array['email'];
            $user->role = 'ROLE_USER    ';
            $user->password = $pwd;

            $user->save();

            $data = array(
                'status' => 'success',
                'code' => '200',
                'message' => 'El usuario registrado correctamente',
            );
        }

        }else{
            $data = array(
                'status' => 'error',
                'code' => '404',
                'message' => 'datos enviados no son correctos',
            );
        }
        return response()->json($data, $data['code']);


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

    public function updatee(Request $request)
    {
        try {
            $user = User::findOrFail(1);
            $password =hash('sha256', $request->password);
            if ($user->password == $password && $user->email == $request->email) {
                $user->email = $request->email;
                $user->password = hash('sha256',$request->newPassword);

                if ($user->update()>=1) {
                    return response()->json(['status'=>'ok', 'data'=>$user], 202);

                } else {
                    return response()->json(['status'=>'fail', 'message' => 'Se produjo un error en el servidor'], 409);
                }
            }else{
                return response()->json(['status'=> 'fails',  'message' => 'La contraseña o correo proporcionada no coincide'],409);
            }


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //comprobar que el usuario esta identidicado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
         //recoger los datos por post
         $json = $request->input('json', null);
         $params_array = json_decode($json, true);
        if($checkToken && !empty($params_array)){


            //sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            //validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'surname' => 'required|alpha',
                'email' => 'required|email|unique:users,'.$user->sub
            ]);

            //quitar los campos que no quiero actualizar
           // unset($params_array['id']);
            //unset($params_array['role']);
            //unset($params_array['password']);
            //unset($params_array['created_at']);
            //unset($params_array['remeber_token']);
            // Acualizar usuario en bbdd
            $user_update = User::where('id', $user->sub)->update($params_array);
            // devolver array con resultado
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array
              );
        }else{
          $data = array(
            'code' => 400,
            'status' => 'error',
            'message' => 'El usuario no esta identificado'
          );
        }
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request){
        $data = array(
            'code' => 400,
            'status' => 'error',
            'message' => 'Error al subir imagen'
          );
       return response()->json($data, $data['code']);
    }

    public function RecoveryPassword(Request $request){
        try{
              //recoger los datos por post
        $params_array = array();
        $params_array['password'] = '';
        $params_array['email'] = $request->email;
       if($params_array['email'] != null){
           //setear los valores que queremos reemplazar
       $pwd = Str::random(16);
       $NewPwd = hash('sha256', $pwd);
       $params_array['password'] = $NewPwd;
       if(User::where('email', $params_array['email'])->update($params_array) > 0){
          app()->call('App\Http\Controllers\SendMailController@sendmail', ['NewPwd' => $pwd]);
          $data = array(
            'code' => 200,
            'status' => 'success',
            'user' => $params_array['email'],
            'changes' => $params_array
          );
       }
       else{
           $data = array(
               'code' => 400,
               'status' => 'error',
               'message' => 'El correo proporcionado no existe'
             );
       }
       }else{
           $data = array(
               'code' => 400,
               'status' => 'error',
               'message' => 'El usuario no esta identificado'
             );
       }
    }catch (\Exception $e) {
        $data = array(
            'code' => 500,
            'status' => 'error',
            'message' => 'Se produjo un error en el servidor'
        );
    }
       return response()->json($data);
   }

    public function destroy(string $id)
    {

    }
}
