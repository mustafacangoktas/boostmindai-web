<?php
/**
 * Timezone API Endpoint
 *
 * Handles GET requests to set and retrieve the user's timezone.
 * - Sets the timezone based on the 'timezone' query parameter.
 *
 * @package API\Timezone
 * @author Mustafa Can
 */

use Core\Controllers\AuthController;

AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

header('Content-Type: application/json');
$timezone = $_GET['timezone'] ?? null;
if ($timezone && in_array($timezone, timezone_identifiers_list())) {
    date_default_timezone_set($timezone);
} else {
    date_default_timezone_set('UTC');
}

$_SESSION['timezone'] = $timezone;

echo json_encode([
    'success' => true,
    'timezone' => date_default_timezone_get(),
    'date' => date('Y-m-d H:i:s')
]);