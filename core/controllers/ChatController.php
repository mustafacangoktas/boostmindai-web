<?php

namespace Core\Controllers;

use Core\Database;
use Exception;

/**
 * Chat Controller
 *
 * Handles user-specific chat functionalities:
 * - Listing chat dates
 * - Retrieving messages for a specific date
 * - Adding messages to today's chat
 *
 * Requires user authentication for all actions.
 *
 * @package Core\Controllers
 */
class ChatController
{
    /**
     * Lists unique chat dates for the current user, with optional filters.
     *
     * Supports:
     * - Optional `before` filter to get dates before a given Y-m-d date.
     * - Optional `limit` to restrict the number of results (default: 10, max: 100).
     *
     * Returns:
     * - An array of dates (Y-m-d format) the user has chatted on.
     * - HTTP 401 if the user is not authenticated.
     * - HTTP 404 if no chat dates found.
     *
     * @param string|null $before Optional date (Y-m-d) to filter dates before this value.
     * @param int $limit Maximum number of dates to return (default: 10).
     *
     * @return array JSON response containing chat dates or an error.
     */
    public static function listChats(?string $before = null, int $limit = 10): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            exit('Unauthorized');
        }

        // Validate limit
        if ($limit < 1) {
            http_response_code(400);
            exit('Invalid limit parameter (must be between 1 and 100)');
        }

        $db = Database::getConnection();

        $query = 'SELECT DISTINCT chat_date FROM chat_messages WHERE user_id = ?';
        $types = 'i';
        $params = [$user['id']];

        // Optional before filter
        if ($before !== null) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $before)) {
                http_response_code(400);
                exit('Invalid "before" date format. Use YYYY-MM-DD.');
            }
            $query .= ' AND chat_date < ?';
            $types .= 's';
            $params[] = $before;
        }

        $query .= ' ORDER BY chat_date DESC LIMIT ?';
        $types .= 'i';
        $params[] = $limit;

        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $dates = [];
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row['chat_date'];
        }

        return [
            'success' => true,
            'dates' => $dates
        ];
    }

    /**
     * Retrieves chat messages for a specific date with optional time filters.
     *
     * This method:
     * - Requires user authentication.
     * - Validates the date format (YYYY-MM-DD).
     * - Supports optional `after` and `before` timestamps to filter messages.
     * - Limits the number of returned messages (default: 10).
     *
     * Behavior:
     * - If both `after` and `before` are provided, messages between these timestamps are returned (exclusive).
     * - If only `after` is provided, messages after the timestamp are returned.
     * - If only `before` is provided, messages before the timestamp are returned.
     *
     * @param string $date The chat date in 'YYYY-MM-DD' format.
     * @param string|null $after Optional timestamp (e.g., '2025-05-30 12:00:00') to get messages after this moment.
     * @param string|null $before Optional timestamp to get messages before this moment.
     * @param int $limit The maximum number of messages to retrieve (default: 10).
     *
     * @return array         An array of chat messages.
     *
     * @throws Exception     If the user is not authenticated or if input validation fails.
     */
    public static function getChat(string $date, ?string $after = null, ?string $before = null, int $limit = 10): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            exit('Unauthorized');
        }

        $db = Database::getConnection();
        $query = 'SELECT m.*, IF(f.id IS NULL, 0, 1) AS is_favorite
  FROM chat_messages m
  LEFT JOIN favorite_messages f ON m.id = f.message_id AND f.user_id = ?
  WHERE m.user_id = ? AND m.chat_date = ?';
        $types = 'iis'; // f.user_id (int), m.user_id (int), m.chat_date (string)
        $params = [$user['id'], $user['id'], $date];

// Apply filters for after/before timestamps
        if ($after !== null && $before !== null) {
            if ($after >= $before) {
                http_response_code(400);
                exit("'after' timestamp must be less than 'before' timestamp.");
            }
            $query .= ' AND m.created_at > ? AND m.created_at < ?';
            $types .= 'ss';
            $params[] = $after;
            $params[] = $before;
        } elseif ($after !== null) {
            $query .= ' AND m.created_at > ?';
            $types .= 's';
            $params[] = $after;
        } elseif ($before !== null) {
            $query .= ' AND m.created_at < ?';
            $types .= 's';
            $params[] = $before;
        }

        $query .= ' ORDER BY m.created_at DESC LIMIT ?';
        $types .= 'i';
        $params[] = $limit;

        $stmt = $db->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            exit('Database query preparation failed.');
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Adds a message to today's chat for the authenticated user.
     *
     * Validates:
     * - User authentication
     * - That the provided date is today
     * - That a message is present
     *
     * @param string $date The chat date in 'Y-m-d' format (must be today).
     * @param string $message The message content.
     * @param string $role The role of the message sender ('user' by default).
     * @return array JSON response indicating success or failure.
     */
    public static function addMessage(string $date, string $message, string $role = 'user'): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            exit('Unauthorized');
        }

        if ($date !== date('Y-m-d')) {
            http_response_code(400);
            exit('Cannot send messages to past chats.');
        }

        if (!$message) {
            http_response_code(400);
            exit('Message required');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO chat_messages (user_id, message, role, chat_date, created_at) VALUES (?, ?, ?, ?, NOW(6))');
        $stmt->bind_param('isss', $user['id'], $message, $role, $date);
        $stmt->execute();

        return [
            'success' => true,
            'message' => 'Message added successfully',
            'data' => [
                'id' => $stmt->insert_id,
                'user_id' => $user['id'],
                'message' => $message,
                'role' => $role,
                'chat_date' => $date,
                'created_at' => date('Y-m-d H:i:s.u', strtotime($stmt->insert_id))
            ]
        ];
    }

    /**
     * Regenerates the assistant's response for a specific message.
     *
     * This method:
     * - Requires user authentication.
     * - Fetches the original assistant message and its chat context.
     * - Calls the AI model to generate a new response based on recent chat history.
     * - Updates the original message in the database with the new response.
     *
     * @param int $messageId The ID of the assistant message to regenerate.
     *
     * @return array JSON response containing the new message or an error.
     *
     * @throws Exception If the user is not authenticated, message not found, or model call fails.
     */
    public static function regenerateAssistantMessage(int $messageId): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            exit('Unauthorized');
        }

        $db = Database::getConnection();
        // Fetch the assistant message and its chat context
        $stmt = $db->prepare('SELECT * FROM chat_messages WHERE id = ? AND user_id = ? AND role = \'assistant\'');
        $stmt->bind_param('ii', $messageId, $user['id']);
        $stmt->execute();
        $msg = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$msg) {
            http_response_code(404);
            throw new Exception('Message not found');
        }

        $today = date('Y-m-d');
        if ($msg['chat_date'] !== $today) {
            http_response_code(400);
            throw new Exception('Cannot regenerate messages from past chats');
        }

        // Fetch recent chat history for the same chat_date
        $stmt = $db->prepare('SELECT * FROM chat_messages WHERE user_id = ? AND chat_date = ? AND created_at < ? ORDER BY created_at ASC LIMIT 15');
        $stmt->bind_param('iss', $user['id'], $msg['chat_date'], $msg['created_at']);
        $stmt->execute();
        $history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $historyForModel = array_map(fn($m) => [
            'role' => $m['role'],
            'content' => $m['message']
        ], $history);

        // Call the model for a new response
        $response = GroqController::callModel('responder_prompts.txt', $historyForModel);

        if (empty($response)) {
            throw new Exception('Failed to generate a response from the AI model');
        }

        // Update the message in the database
        $stmt = $db->prepare('UPDATE chat_messages SET message = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('sii', $response, $messageId, $user['id']);
        $stmt->execute();
        $stmt->close();

        return [
            'success' => true,
            'message' => $response
        ];
    }

    /**
     * Deletes all messages for a specific chat date.
     *
     * This method:
     * - Requires user authentication.
     * - Deletes all messages for the specified date for the authenticated user.
     *
     * @param string $date The chat date in 'Y-m-d' format.
     *
     * @return array JSON response indicating success or failure.
     */
    public static function deleteChat(string $date): array
    {
        $user = AuthController::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            exit('Unauthorized');
        }
        $db = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM chat_messages WHERE user_id = ? AND chat_date = ?');
        $stmt->bind_param('is', $user['id'], $date);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();
        return [
            'success' => true,
            'deleted' => $deleted
        ];
    }
}
