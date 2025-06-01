<?php
/**
 * Routes configuration for the application.
 * This file defines the routes for the application, including static pages and API endpoints.
 * It uses a router to handle requests and includes necessary files for each route.
 */

use Core\Services\I18n;

if (!isset($router)) {
    die('Router not initialized');
}

$router->get('/', fn() => require 'pages/index.php');

$router->get('/chat/{id}', fn($id) => require 'pages/chat.php');
$router->get('/{lang}/chat/{id}', fn($lang, $id) => require 'pages/chat.php');

$router->post('/api/chat/{id}', fn($id) => $id == "regenerate" ?
    require 'pages/api/chat/regenerate.php' :
    ($id == "delete" ?
        require 'pages/api/chat/delete.php' :
        require 'pages/api/chat.php'
    )
);

// Catch-all for /api requests (GET, POST, etc.)
foreach (["get", "post", "put", "delete"] as $method) {
    $router->$method('/{path:.+}', function ($path) use ($method) {
        $path = str_starts_with($path, '/api') ? $path : I18n::removeLanguagePrefix($path);
        if ($path === "/") $path = "index";
        $file = __DIR__ . "/pages/" . $path . ".php";
        if (file_exists($file)) {
            require $file;
        } else {
            http_response_code(404);
            require __DIR__ . '/pages/404.php';
        }
    });
}
