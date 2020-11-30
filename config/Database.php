<?php
namespace Config;
//CUANDO CREO NAMESPACE TENGO QUE AGREGARLO AL AUTOLOAD PSR 4 DEL COMPOSER JSON
//Y TENGO QUE HACER EL COMPOSER DUMP AUTOLOAD EN CONSOLA PARA QUE RECONOZOCA
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

//ORM EMPAREJA UNA TABLA CON UN OBJETO (MODELO) QUE TENEMOS QUE DEFINIR
//este archivo hace la conexion con la base de datos. para no teener que hacer
//la conezcion cada vez, lo pongo en un clase
class Database{
    public function __construct()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'us-cdbr-east-02.cleardb.com',
            'database'  => 'heroku_2e07dbb5ab1d714', 
            'username'  => 'bbf16ae29f4915',
            'password'  => '6b229cc6',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }
}

