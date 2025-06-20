<?php
/**
 * Logout API Endpoint
 *
 * Clears the user session and deletes any persistent authentication tokens (cookies).
 * Returns a JSON response indicating logout status.
 *
 * @package API\Auth
 * @author Mustafa Can
 */

use Core\Controllers\AuthController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

if (!AuthController::isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

AuthController::logout();

http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);