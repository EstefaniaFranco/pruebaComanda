<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class ValidarTokenMiddleware{

    private static $key = 'key';

    public function __invoke(Request $request, RequestHandler $handler): Response
    {        
        $response = new Response();        
        $headers = getallheaders();
        $token = $headers['token'] ?? '';
            
        try {
            $valido = JWT::decode($token, ValidarTokenMiddleware::$key, array('HS256'));
        } catch (\Throwable $th) {
            $msj = $th->getMessage();
            $valido = null;
        }

        if ($valido == null) {
            $response = new Response();
            $response->getBody()->write($msj);
            return $response->withStatus(403);
        } else {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }

    }
}
?>

