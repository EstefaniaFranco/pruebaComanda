<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Pedido;
use App\Models\Ordene;


class PedidosController{
    static function generateCode(){
        $permitted_chars = 'ABCDEFGHIJKLMtuvwxyz01234abcdefghijk56789lmnopqrsNOPQRSTUVWXYZ';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < 5; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }

    public function addOne(Request $request, Response $response) {
        $pedido = new Pedido;
        $success = false;
        
        try {
            $body = $request->getParsedBody(); 
            $pedido->cliente = $body['cliente'];
            $pedido->id_mozo = $body['id_mozo'];
            $pedido->cod_mesa = $body['mesa'];
            $pedido->codigo = PedidosController::generateCode();
            $pedido->save();

           foreach ($body['orden'] as $menu) {
               $orden = new Ordene;
               $orden->cod_menu = $menu['menu'];
               $orden->cantidad = $menu['cantidad'];
               $orden->cod_pedido = $pedido->codigo;
               $orden->estado = 1;
               $orden->save();
           }
           
           $msg = $pedido;
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


}

?>
