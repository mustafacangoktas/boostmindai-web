<?php
/**
 * Chat Regeneration API Endpoint
 *
 * Handles regeneration of assistant messages for a specific chat message.
 * Requires user authentication and expects a POST request with a valid message ID.
 *
 * @package API\Chat
 */

use Core\Controllers\AuthController;
use Core\Controllers\ChatController;

header('Content-Type: application/json');
AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$messageId = $_POST['message_id'] ?? null;
if (!$messageId || !is_numeric($messageId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
    exit;
}

try {
    $result = ChatController::regenerateAssistantMessage((int)$messageId);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}