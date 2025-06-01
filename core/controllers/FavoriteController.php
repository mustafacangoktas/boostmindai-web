<?php

namespace Core\Controllers;

use Core\Database;

class FavoriteController
{
    public static function addFavorite(int $messageId): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        $db = Database::getConnection();
        $stmt = $db->prepare('INSERT IGNORE INTO favorite_messages (user_id, message_id, created_at) VALUES (?, ?, NOW())');
        $stmt->bind_param('ii', $user['id'], $messageId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return ['success' => $success];
    }

    public static function removeFavorite(int $messageId): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        $db = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM favorite_messages WHERE user_id = ? AND message_id = ?');
        $stmt->bind_param('ii', $user['id'], $messageId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return ['success' => $success];
    }

    public static function listFavorites(): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT m.* FROM favorite_messages f JOIN chat_messages m ON f.message_id = m.id WHERE f.user_id = ? ORDER BY f.created_at DESC');
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return ['success' => true, 'data' => $messages];
    }
}