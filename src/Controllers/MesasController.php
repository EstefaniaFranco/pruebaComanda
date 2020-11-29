<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mesa;

class MesasController{
    public function addOne(Request $request, Response $response) {
        $mesa = new Mesa;
        $success = false;
        try {
    
            $body = $request->getParsedBody(); 
            $mesa->estado_id = $body['estado'] ?? 4;
            $mesa->capacidad = intval($body['capacidad']);  
            $mesa->save();
            $msg = $mesa;
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

    public function deleteOne(Request $request,Response $response, $args) {
        $mesa = Mesa::find(intval($args['id']));
        $success = false;
        try {
            if($args['delete'] == 'delete'){ 
                $msg =  $mesa->delete();
                $success = true;   
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

    public function updateOne(Request $request, Response $response, $args) {
        $mesa = Mesa::find($args['id']);
        $success = false;

        try {
            $body = $request->getParsedBody(); 
            $mesa->estado_id = $body['estado'] ?? $mesa->estado_id;
            $mesa->capacidad = $body['capacidad'] ?? $mesa->capacidad;

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

    // public function changeStatus(Request $request, Response $response, $args){    
    //     $success = false;           
    //         try {

    //             if($args['estado'] == 3){
    //                 $msg = "Pedido actualizado";
    //             }else{

    //                 $msg = "no se pudo kpa";
    //             }

    //             // $ordenes = Ordene::where('cod_pedido','=', $args['cod'])->join('menus', 'cod_menu', '=', 'menus.id')
    //             // ->where('menus.sector', '=', $args['sector'])->select('ordenes.id')->get();
    
    //             // $orden = new Ordene;
    //             // foreach($ordenes as $id){       
    //             //    $orden = $orden->find($id->id);
    //             //    $orden->id_empleado = $emp->id;
    //             //    $orden->estado = $request->getParsedBody()['estado'];
    //             //    $orden->save();
    //             //    $emp->operaciones++;
    //             //    $emp->save();
    //             // }
    //             $success = true;  
                
    
    //         } catch (\Throwable $th) {
    //             $msg = "Error: " .$th->getMessage();
    //         }
       
    //     $rta = array("success" => $success,
    //         "mensaje" => $msg
    //     );
    //     $response->getBody()->write( json_encode($rta)); 
    //     return $response;
    // }
}

?>