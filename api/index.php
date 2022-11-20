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
