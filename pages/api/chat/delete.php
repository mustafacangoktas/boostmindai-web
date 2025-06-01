<?php
use Core\Controllers\AuthController;
use Core\Controllers\ChatController;

header('Content-Type: application/json');
AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$chatDate = $_POST['chat_date'] ?? null;
if (!$chatDate || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $chatDate)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid chat date']);
    exit;
}

try {
    $result = ChatController::deleteChat($chatDate);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}