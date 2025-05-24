<?php
if (!isset($router)) {
    die('Router not initialized');
}
$router->get('/', fn() => require 'pages/index.php');
$router->get('/chat', fn() => require 'pages/chat.php');
$router->get('/chat/{id}', fn($id) => require 'pages/chat.php');
$router->get('/privacy', fn() => require 'pages/privacy.php');
$router->get('/cookies', fn() => require 'pages/cookies.php');
$router->get('/terms', fn() => require 'pages/terms.php');
$router->get('/register', fn() => require 'pages/register.php');