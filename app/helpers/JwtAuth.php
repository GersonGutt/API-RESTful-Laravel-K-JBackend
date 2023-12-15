<?php
namespace App\helpers;

use App\Models\User;
use Firebase\JWT\JWT;
use illuminate\Support\Facades\DB;

class JwtAuth{

    public $key;
    public function __construct(){
        $this->key = 'AL,HSKknksbisbiaa';
    }
    public function singup($email, $password, $getToken = null){
    // Buscar si existe el ususario con sus credenciales
    $user = User::where([
        'email' => $email,
        'password' => $password
    ])->first();
    // Comprobar si son correctas(objeto)
    $singup = false;
    if(is_object($user)){
        $singup = true;
    }
    // Generar el roken con los datos del usuario identificado
    if($singup){

        $token = array(
            'sub' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60),
        );

        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']);
// Devolver los datos decodificados o el token, en funcion de un parametro
      if (is_null($getToken)){
       $data = $jwt;
      }
      else{
        $data = $decoded;
      }
    }else{
        $data = array(
            'status' => 'error',
            'code' => '404',
            'message' => 'Login failed',
            'errors' => [
                'error'=>'Correo o Contraseña incorrecta.',
            ],
        );
    }
      return $data;
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }
        if (!empty($decoded) &&  is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        } else{
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }
}



?>
