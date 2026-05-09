<?php session_start();

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$routes = require __DIR__ . '/../app/routes.php';
$path   = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$route  = $routes[$path] ?? null;

if (!$route) {
    http_response_code(404);
    require __DIR__ . '/../app/Views/404.php';
    exit;
}

if ($route instanceof Closure) {
    $route();
    exit;
}

if (!is_array($route) || count($route) !== 2) {
    http_response_code(500);
    exit('Invalid route definition');
}

[$controller, $method] = $route;

if (!class_exists($controller) || !method_exists($controller, $method)) {
    http_response_code(500);
    exit('Controller or method not found');
}

(new $controller)->$method();