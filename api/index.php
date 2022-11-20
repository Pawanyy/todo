<?php
declare(strict_types=1);

require dirname(__DIR__) . "/bootstrap.php";

header('Content-Type: application/json; charset=utf-8');

$router = new AltoRouter();

$router->setBasePath('/api');

$router->map( 'GET|POST', '/', function() {

    echo json_encode([
        "status" => 'success',
        "message" => "welcome to API",
    ]);

});
