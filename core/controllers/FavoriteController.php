<?php

namespace Core\Controllers;

use Core\Database;

/**
 * Favorite Messages Controller
 *
 * Handles:
 * - Adding a message to favorites
 * - Removing a message from favorites
 * - Listing favorite messages for the authenticated user
 *
 * This controller requires user authentication and interacts with the database
 *
 * @package Core\Controllers
 */
class FavoriteController
{

    /**
     * Adds a message to the user's favorites.
     *
     * @param int $messageId The ID of the message to add to favorites.
     * @return array An associative array indicating success or failure.
     */
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

    /**
     * Removes a message from the user's favorites.
     *
     * @param int $messageId The ID of the message to remove from favorites.
     * @return array An associative array indicating success or failure.
     */
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

    /**
     * Lists all favorite messages for the authenticated user.
     *
     * @return array An associative array containing the list of favorite messages.
     */
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