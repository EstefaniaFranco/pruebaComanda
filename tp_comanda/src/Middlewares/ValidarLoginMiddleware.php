<?php

namespace App\Middlewares;

use App\Models\Empleado;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarLoginMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $usuario = new Empleado;
        $body = $request->getParsedBody();
        $id = $usuario->where('id', $body['id'])->exists();

        if ($id) {
            $mail = $usuario->where('id', $body['id'])->value('email');
            $password = $usuario->where('id', $body['id'])->value('password');

            if ($mail == $body['email'] && $password == $body['password']) {
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
            } else {
                $response->getBody()->write('Usuario y/o Contraseña incorrecta');
            }
            
        } else {
            $response->getBody()->write('Usuario inexistente');
        }

        return $response;
    }
}
