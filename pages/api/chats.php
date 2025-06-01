<?php
/**
 * Chats API Endpoint
 *
 * Handles GET requests to list chat dates with optional pagination.
 * - Supports filtering by date before a specified date.
 * - Limits the number of results returned (default: 10, max: 100).
 *
 * @package API\Chats
 * @author Mustafa Can
 */

use Core\Controllers\AuthController;
use Core\Controllers\ChatController;

AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

header('Content-Type: application/json');

$before = $_GET['before'] ?? null;
$limit = $_GET['limit'] ?? 10;

if (!is_numeric($limit) || $limit < 1 || $limit > 10) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid limit. Must be between 1 and 10.'
    ]);
    exit;
}

// "before" date validation
if ($before !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $before)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid "before" date format. Use YYYY-MM-DD.'
    ]);
    exit;
}

// Fetch chat dates
try {
    $result = ChatController::listChats($before, (int)$limit);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
