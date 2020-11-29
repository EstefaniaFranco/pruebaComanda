<?php

use App\Controllers\EmpleadosController;
use App\Controllers\MesasController;
use App\Controllers\MenusController;
use App\Controllers\PedidosController;
use App\Controllers\LoginsController;
use Slim\Factory\AppFactory;
use Config\Database;
use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\ValidarLoginMiddleware;
use App\Middlewares\ValidarDatosMiddleware;
use App\Middlewares\ValidarTokenMiddleware;
use App\Middlewares\IsAdminMiddleware;

require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();
$app->setBasePath('/public');

new Database;

// Login, verifico si el usuario existente y genero el token
$app->post('/login[/]', LoginsController::class . ':login')->add(new ValidarLoginMiddleware)->add(new JsonMiddleware);

// ABM empleado
$app->group('/empleado', function (RouteCollectorProxy $group) {   
    $group->post('[/]', EmpleadosController::class . ':addOne')->add(new ValidarDatosMiddleware);
    $group->delete('/{id}[/]', EmpleadosController::class . ':deleteOne');
    $group->put('/{id}[/]', EmpleadosController::class . ':updateOne');
    $group->get('[/]', EmpleadosController::class . ':getAll');
})->add(new JsonMiddleware);

//->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)
// ABM mesas
$app->group('/mesa', function (RouteCollectorProxy $group) {   
    $group->post('[/]', MesasController::class . ':addOne');
    $group->post('/{id}/{delete}', MesasController::class . ':deleteOne');
    $group->post('/{id}[/]', MesasController::class . ':updateOne');
  //  $group->post('/{id}-{estado}[/]', MesasController::class . ':changeStatus');
})->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);

// ABM menu
$app->group('/menu', function (RouteCollectorProxy $group) {   
    $group->post('[/]', MenusController::class . ':addOne');
    $group->delete('/{id}[/]', MenusController::class . ':deleteOne');
    $group->put('/{id}[/]', MenusController::class . ':updateOne');
})->add(new IsAdminMiddleware)->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);

$app->group('/pedido', function (RouteCollectorProxy $group) {   
    $group->post('[/]', PedidosController::class . ':addOne');
    $group->post('/{cod}/{sector}[/]', PedidosController::class . ':changeStatus');

})->add(new ValidarTokenMiddleware)->add(new JsonMiddleware);







// $app->group('/materia', function (RouteCollectorProxy $group) {

//     //PUNTO3: Carga una materia (solo admin)
//     $group->post('[/]', MateriasController::class . ':addMateria')->add(new CuatrimestreValidoMiddleware)->add(new IsAdminMiddleware);

//     //PUNTO 7: Muestra listado de materias
//     $group->get('[/]', MateriasController::class . ':getAll');


// })->add(new TokenValidoMiddleware)->add(new JsonMiddleware);


// $app->group('/inscripcion', function (RouteCollectorProxy $group) {

//     //PUNTO 4: Inscripcion a materia (solo alumno)
//     $group->post('/{id}[/]', InscripcionController::class . ':inscribirMateria')->add(new IsAlumnoMiddleware);

//     //PUNTO 6: Listado de alumnos de una materia (solo admin y profesor)
//     $group->get('/{id}[/]', InscripcionController::class . ':listadoPorMateria')->add(new IsNotAlumnoMiddleware);

// })->add(new TokenValidoMiddleware)->add(new JsonMiddleware);

$app->addBodyParsingMiddleware();
$app->run();
