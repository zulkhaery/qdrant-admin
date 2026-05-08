<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$routes = require __DIR__ . '/../app/routes.php';

$route = $routes[$path] ?? null;

if (!$route) {
    http_response_code(404);
    exit('Page not found');
}

if ($route instanceof Closure) {
    $route();
    exit;
}

[$controller, $method] = $route;

(new $controller)->$method();