<?php

use Core\Controllers\AuthController;
use Core\Controllers\FavoriteController;
use Core\Database;

header('Content-Type: application/json');
AuthController::requireAuthentication();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(FavoriteController::listFavorites());
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageId = (int)($_POST['message_id'] ?? 0);
    if (!$messageId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
        exit;
    }

    // Limit: 30 favorites per user
    $user = AuthController::getCurrentUser();
    $db = Database::getConnection();
    $stmt = $db->prepare('SELECT COUNT(*) FROM favorite_messages WHERE user_id = ?');
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    $stmt->bind_result($favCount);
    $stmt->fetch();
    $stmt->close();

    if ($favCount >= 30) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'You can only have up to 30 favorite messages.']);
        exit;
    }

    echo json_encode(FavoriteController::addFavorite($messageId));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $messageId = (int)($data['message_id'] ?? 0);
    if (!$messageId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
        exit;
    }
    echo json_encode(FavoriteController::removeFavorite($messageId));
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);