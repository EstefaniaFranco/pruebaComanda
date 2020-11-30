<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Ordene;
use \Firebase\JWT\JWT;
use App\Models\Empleado;

class OrdenesController{
    public function changeStatus(Request $request, Response $response, $args){    
        $success = false;           
        $emp = new Empleado;
        $emp = $emp::find(JWT::decode(getallheaders()['token'], 'key', array('HS256'))->id);

        if($emp->tipo_id == $args['sector']){
            try {
                $ordenes = Ordene::where('cod_pedido','=', $args['cod'])->join('menus', 'cod_menu', '=', 'menus.id')
                ->where('menus.sector', '=', $args['sector'])->select('ordenes.id')->get();
    
                $orden = new Ordene;
                foreach($ordenes as $id){       
                   $orden = $orden->find($id->id);
                   $orden->id_empleado = $emp->id;
                   $orden->estado = $request->getParsedBody()['estado'];
                   $orden->save();
                   $emp->operaciones++;
                   $emp->save();
                }
                $success = true;  
                $msg = "Pedido actualizado";
    
            } catch (\Throwable $th) {
                $msg = "Error: " .$th->getMessage();
            }
        }else{
            $msg = 'Usted no tiene permiso para realizar esta accion.';
        }
       
        $rta = array("success" => $success,
            "mensaje" => $msg
        );
        $response->getBody()->write( json_encode($rta)); 
        return $response;
    }
}

?>