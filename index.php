<?php
/**
 * Main entry point for the application
 * This file initializes the application, sets up routing, and handles requests.
 * It includes configuration, utility functions, and the router.
 */


define("config", require 'config.php');

include_once "utils/i18n.php";
require_once 'utils/auth.php';

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
