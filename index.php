<?php

include_once "includes/common/i18n.php";
require 'router/Router.php';

$router = new Router();

$requestUri = $_SERVER['REQUEST_URI'];
// Static assets handling
if (preg_match('/^\/assets\//', $requestUri)) {
    $filePath = __DIR__ . $requestUri;
    if (file_exists($filePath)) {
        header('Content-Type: ' . mime_content_type($filePath));
        readfile($filePath);
        exit;
    }
}

require 'routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($requestUri, PHP_URL_PATH));
