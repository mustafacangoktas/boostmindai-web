<?php
/**
 * Main entry point for the application
 *
 * - Loads configuration and initializes services
 * - Registers basic autoloader
 * - Handles static assets
 * - Initializes router and dispatches the request
 *
 * @author Mustafa Can
 */

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/services/I18n.php';
require_once __DIR__ . '/core/controllers/AuthController.php';
require_once __DIR__ . '/core/controllers/CaptchaController.php';

use Core\Controllers\AuthController;
use Core\Database;
use Core\Services\I18n;

// Load i18n and auth manually (non-class based)
require_once __DIR__ . '/core/services/I18n.php';
require_once __DIR__ . '/core/controllers/AuthController.php';

// Handle static asset requests directly
$requestUri = $_SERVER['REQUEST_URI'];
if (preg_match('/^\/assets\//', $requestUri)) {
    $filePath = __DIR__ . $requestUri;
    if (file_exists($filePath)) {
        header('Content-Type: ' . mime_content_type($filePath));
        readfile($filePath);
        exit;
    }
}

// Initialize router
require_once __DIR__ . '/core/Router.php';
$router = new Router();

// Initialize i18n and auth controllers
Database::initialize();
AuthController::initialize();

// Load routes
require_once __DIR__ . '/routes.php';

// Dispatch route
$path = parse_url($requestUri, PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);

/**
 * Translates a key using the loaded translations.
 *
 * @param string $key The key to translate
 * @return string The translated string, or the key itself if not found
 */
function t(string $key): string
{
    if (empty(I18n::$translations)) {
        I18n::loadTranslations();
    }
    return I18n::$translations[$key] ?? $key;
}
