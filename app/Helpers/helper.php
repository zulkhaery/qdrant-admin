<?php

function view(string $view, array $data = [])
{
    $data['currentPath'] = parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

    extract($data);

    require __DIR__ . '/../Views/layouts/main.php';
}