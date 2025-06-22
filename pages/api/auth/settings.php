<?php
/**
 * User Settings API Endpoint
 *
 * Handles user settings retrieval and updates, including name and password changes.
 * Requires authentication and returns JSON responses.
 *
 * @package API\Auth
 */

use Core\Controllers\AuthController;

header('Content-Type: application/json');
AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(AuthController::getSettings());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $oldPassword = $_POST['old_password'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name === '' || strlen($name) < 2 || strlen($name) > 50) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Name must be between 2 and 50 characters.']);
        exit;
    }

    if ($password !== '' && (strlen($password) < 8 || strlen($password) > 32)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must be between 8 and 32 characters.']);
        exit;
    }

    if ($oldPassword !== '' && (strlen($oldPassword) < 8 || strlen($oldPassword) > 32)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Old password must be between 8 and 32 characters.']);
        exit;
    }

    echo json_encode(AuthController::updateSettings($name, $oldPassword, $password));
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);