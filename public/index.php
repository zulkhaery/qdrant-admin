<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(
    __DIR__ . '/../'
);

$dotenv->load();

$routes = require __DIR__ . '/../app/routes.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!isset($routes[$path])) {
    http_response_code(404);
    exit('404 Not Found');
}

[$class, $method] = $routes[$path];

$controller = new $class();

echo $controller->$method();