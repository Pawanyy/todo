<?php
declare(strict_types=1);

require dirname(__DIR__) . "/bootstrap.php";

header('Content-Type: application/json; charset=utf-8');

$router = new AltoRouter();

$router->setBasePath('/api');

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$router->map( 'GET|POST', '/', function() {

    echo json_encode([
        "status" => 'success',
        "message" => "welcome to API",
    ]);

});

$router->map( 'GET', '/tasks', function() use ($database) {

    $task_gateway = new TaskGateway($database);
    $controller = new TaskController($task_gateway);
    $controller -> getAllTasks();

});

$router->map( 'GET', '/tasks/[a:state]', function($state) use ($database) {

    $task_gateway = new TaskGateway($database);
    $controller = new TaskController($task_gateway);
    $controller -> getTasks($state);

});

$router->map( 'POST', '/task', function() use ($database) {

    $task_gateway = new TaskGateway($database);
    $controller = new TaskController($task_gateway);
    $controller -> createTask();

});

$router->map( 'POST', '/task/[i:id]', function($id) use ($database) {

    $task_gateway = new TaskGateway($database);
    $controller = new TaskController($task_gateway);
    $controller -> updateTask($id);

});

$router->map( 'DELETE', '/task/[i:id]', function($id) use ($database) {

    $task_gateway = new TaskGateway($database);
    $controller = new TaskController($task_gateway);
    $controller -> deleteTask($id);

});

$match = $router->match();

// call closure or throw 404 status
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	// no route was matched
    // throw 404 status
    http_response_code(404);
    echo json_encode(['Message' => 'Resource Not Developed Yet!!!']);
    exit;
}

?>