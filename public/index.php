<?php 

define("ROOT_PATH", dirname(__DIR__));

require_once ROOT_PATH.'/vendor/autoload.php';
use App\Core\App;
use App\Controllers;


$app = new App();

$app->router->get('/', function(){
    $taskController = new Controllers\TaskController;
    $taskController->getAll();
});

$app->router->get('/tasks', [Controllers\TaskController::class, 'getAll']);
$app->router->get('/tasks/@id:(\d+)', [Controllers\TaskController::class, 'get']);

$app->router->get('/tasks/add', [Controllers\TaskController::class, 'add']);

$app->router->get('/tasks/update/@id:(\d+)', [Controllers\TaskController::class, 'update']);

$app->router->post('/tasks/add/', [Controllers\TaskController::class, 'add']);

$app->router->post('/tasks/update/@id:(\d+)', [Controllers\TaskController::class, 'update']);
$app->router->post('/tasks/update/', [Controllers\TaskController::class, 'add']);

$app->router->get('/tasks/delete/@id:(\d+)', [Controllers\TaskController::class, 'delete']);

$app->run();