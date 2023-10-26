<?php

namespace App\Core;

use App\Core\Router;
use App\Core\Db;
class App{

    public  \PDO $db;
    public Router $router;
    public static self $app;

    public function __construct()
    {
        $this->router = new Router();
    }
    
   
    /**
     * Runs the application by initializing the database connection, setting the current instance of the application,
     * and resolving the current route using the router.
     *
     * @return void
     *
     * @throws \Exception If the database connection cannot be established.
     */
    public function run():void
    {
        $this->db = Db::getDB();
        self::$app = $this;
        $this->router->resolve();
    }


}