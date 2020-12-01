<?php

namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class AdminMozoMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {         
            $response = new Response();
            $token = getallheaders() ['Token'] ?? '';
            $decoded = JWT::decode($token, 'key', array('HS256'));  
        
            if ($decoded->tipo_id == 1 || $decoded->tipo_id == 5) {     
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $rta = array("success" => false,
                    "mensaje" => 'Usted no tiene permiso para realizar esta accion.');
                $response->getBody()->write(json_encode($rta));
            }        
            return $response;
        }
}
?>