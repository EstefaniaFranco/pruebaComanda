<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mesa;
use \Firebase\JWT\JWT;

class MesasController{

    public function addOne(Request $request, Response $response) {
        $mesa = new Mesa;
        $success = false;
        try {
            $body = $request->getParsedBody(); 
            if($body['estado'] > 0 && $body['estado'] < 5){
                $mesa->estado_id = $body['estado'];
                $mesa->codigo = rand(11111, 99999);
                $mesa->capacidad = intval($body['capacidad']);  
                $mesa->save();
                $msg = $mesa;
                $success = true;  
            }
            else{
                $msg = 'Estado de mesa incorrecto';
            }
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
            "mensaje" => $msg
        );
    
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteOne(Request $request,Response $response, $args) {
        $mesa = Mesa::find(intval($args['id']));
        $success = false;
        try {
            $msg =  $mesa->delete();
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
        $mesa = Mesa::find($args['id']);
        $success = false;
      //  $emp = JWT::decode(getallheaders()['Token'], 'key', array('HS256'));

        try {
            $body = $request->getParsedBody(); 
            $mesa->capacidad = intval($body['capacidad']) ?? $mesa->capacidad;

            if(isset($body['estado'])){
                // if($body['estado'] == 1 && $emp->tipo_id != 1){
                //     $msg = 'Usted no tiene permiso para cerrar la mesa.';
                // }else{
                    $mesa->estado_id = $body['estado'];
               // }
            }      
            $mesa->save();
            $msg = $mesa;
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