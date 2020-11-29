<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Empleado;
use App\Models\Login;
use \Firebase\JWT\JWT;

class LoginsController{
    //hace el login y creo el token
    public function login(Request $request, Response $response) { 
        $emp = new Empleado;
        $key = 'key';
        $success = false;
        try {
            $body = $request->getParsedBody();
            $payload = array (
                "id"    => $emp->where('id', $body['id'])->value('id'),
                "email" => $emp->where('id', $body['id'])->value('email'),
                "tipo_id" => $emp->where('id', $body['id'])->value('tipo_id'),
                "password" => $emp->where('id', $body['id'])->value('password'),
                "nombre"=> $emp->where('id', $body['id'])->value('nombre'),
                "apellido" => $emp->where('id', $body['id'])->value('apellido'),
            );

            $login = new Login;
            $login->cod_empleado = $emp->where('id', $body['id'])->value('id');
            $login->save();
            $msg = JWT::encode($payload,$key);
            $success = true;     
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
                "mensaje" => $msg
        );
    
        $response->getBody()->write( json_encode($rta));
        return $response;
    }
}

?>