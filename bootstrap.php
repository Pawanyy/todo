<?php
declare(strict_types = 0);

require __DIR__ . "/vendor/autoload.php";


date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv -> load();

# define BASE_URL constant if not defined already
if( ! defined ('BASE_URL'))
    define('BASE_URL', $_ENV['BASE_URL']);


if($_ENV['ERROR_SHOW']){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

if($_ENV['ERROR_LOG']){
    error_reporting(E_ALL);
    ini_set("log_errors", 1); // Enable error logging
    ini_set("error_log", __DIR__  . "/storage/logs/error_log.log"); // set error path
}
?>