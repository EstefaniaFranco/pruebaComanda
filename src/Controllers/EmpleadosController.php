<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Empleado;
use \Firebase\JWT\JWT;

class EmpleadosController{
    //Muestra listado de usuarios?
    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Empleado::get());
        $response->getBody()->write($rta);
        return $response;
    }

    // se registra un nuevo empleado 
    public function addOne(Request $request, Response $response) {
        
        $emp = new Empleado;
        $success = false;
        try {
            $body = $request->getParsedBody(); 
            $emp->nombre = $body['nombre'];
            $emp->apellido = $body['apellido'];
            $emp->tipo_id = intval($body['tipo']);
            $emp->email = $body['email'];
            $emp->password =$body['password'];      
            $emp->save();  
            $msg = $emp;
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

    public function deleteOne(Request $request, Response $response, $args) {
        $emp = Empleado::find($args['id']);
        $success = false;
        try {  
            $msg = $emp->delete();
            $success = true;   

        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
            "mensaje" => $msg
        );
    
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function updateOne(Request $request, Response $response, $args) {
        $emp = Empleado::find($args['id']);
        $success = false;

        try {
            $body = $request->getParsedBody(); 
            $emp->nombre = $body['nombre'] ?? $emp->nombre;
            $emp->apellido = $body['apellido'] ?? $emp->apellido;
            $emp->tipo_id = $body['tipo'] ?? $emp->tipo_id;
            $emp->email = $body['email'] ??  $emp->email;
            $emp->password =$body['password'] ??  $emp->password;
            $emp->save();  
            $msg = $emp;
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