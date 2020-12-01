<?php

use App\Controllers\EmpleadosController;
use App\Controllers\MesasController;
use App\Controllers\MenusController;
use App\Controllers\PedidosController;
use App\Controllers\LoginsController;
use App\Controllers\OrdenesController;
use Slim\Factory\AppFactory;
use Config\Database;
use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\ValidarLoginMiddleware;
use App\Middlewares\ValidarDatosMiddleware;
use App\Middlewares\ValidarTokenMiddleware;
use App\Middlewares\IsAdminMiddleware;

require __DIR__ . '/vendor/autoload.php';
$app = AppFactory::create();

new Database;

// Login, verifico si el usuario existente y genero el token
$app->post('/login[/]', LoginsController::class . ':login')->add(new ValidarLoginMiddleware)->add(new JsonMiddleware);

// ABM empleado
$app->group('/empleado', function (RouteCollectorProxy $group) {   
    $group->post('[/]', EmpleadosController::class . ':addOne')->add(new ValidarDatosMiddleware);
    $group->delete('/{id}[/]', EmpleadosController::class . ':deleteOne');
    $group->put('/{id}[/]', EmpleadosController::class . ':updateOne');
    $group->get('[/]', EmpleadosController::class . ':getAll');
})->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);





// ABM mesas
$app->group('/mesa', function (RouteCollectorProxy $group) {   
    $group->post('[/]', MesasController::class . ':addOne');
    $group->delete('/{id}[/]', MesasController::class . ':deleteOne');
    $group->put('/{id}[/]', MesasController::class . ':update' );
})->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);









// ABM menu
$app->group('/menu', function (RouteCollectorProxy $group) {   
    $group->post('[/]', MenusController::class . ':addOne');
    $group->delete('/{id}[/]', MenusController::class . ':deleteOne');
    $group->put('/{id}[/]', MenusController::class . ':updateOne');

})->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);


//PEDIDOS
$app->group('/pedido', function (RouteCollectorProxy $group) {   
    $group->post('[/]', PedidosController::class . ':addOne');
})->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);

//ORDENES
$app->group('/orden', function (RouteCollectorProxy $group) {   
    $group->put('/{cod}-{sector}[/]', OrdenesController::class . ':changeStatus');
})->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);




$app->addBodyParsingMiddleware();
$app->run();
