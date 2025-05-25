<?php
if (!isset($router)) {
    die('Router not initialized');
}
$router->get('/', fn() => require 'pages/index.php');
$router->get('/chat/{id}', fn($id) => require 'pages/chat.php');

foreach (["get", "post", "put", "delete"] as $method) {
    $router->$method('/{path:.+}', function ($path) use ($method) {
        $file = __DIR__ . "/pages/" . $path . ".php";
        if (file_exists($file)) {
            require $file;
        } else {
            http_response_code(404);
            require __DIR__ . '/pages/404.php';
        }
    });
}