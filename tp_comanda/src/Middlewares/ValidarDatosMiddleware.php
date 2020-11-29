<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarDatosMiddleware{
    public function __invoke(Request $request, RequestHandler $handler): Response
        {     
            $response = new Response();

            $body =$request->getParsedBody();
            
            if (isset($body['email']) && isset($body['password']) && isset($body['tipo']) && isset($body['nombre']) && isset($body['apellido'])) {
                if($body['email'] == "" || $body['password'] == "" || $body['tipo'] == "" || $body['nombre'] == "" && $body['apellido'] == ""){
                    $response->getBody()->write('Faltan Completar Datos');
                }else {
                    if($body['tipo'] > 0 && $body['tipo'] < 6) {
                        $existingContent = (string) $response->getBody();
                        $response = $handler->handle($request);
                        $response->getBody()->write($existingContent);
                    }else {
                        $response->getBody()->write('Tipo de Empleado invalido.');
                    }        
                }
            } else {
                $response->getBody()->write('Faltan datos');  
            }
            
            return $response;
        }
}


?>