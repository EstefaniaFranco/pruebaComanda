<?php

namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Menu;

class MenusController{
    public function addOne(Request $request, Response $response) {
        $menu = new Menu;
        $success = false;

        try {
            $body = $request->getParsedBody(); 
            $menu->nombre = $body['nombre'];
            $menu->precio = $body['precio'];
            $menu->sector = $body['sector'];  
            $menu->save();
            $msg = $menu;
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
        $menu = Menu::find($args['id']);
        $success = false;
        try {  
            $msg = $menu->delete();
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
        $menu = Menu::find($args['id']);
        $success = false;

        try {
            $body = $request->getParsedBody(); 
            $menu->nombre = $body['nombre'] ??  $menu->nombre;
            $menu->precio = $body['precio'] ??  $menu->precio;
            $menu->sector = $body['sector'] ??  $menu->sector;
            $menu->save();
            $msg = $menu;
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