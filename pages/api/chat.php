<?php
/**
 * Chat API Endpoint
 *
 * Handles GET and POST requests for chat messages.
 * - GET: Fetches chat messages for a specific date with optional pagination.
 * - POST: Adds a new message and returns a response from the AI model.
 *
 * @package API\Chat
 */
header('Content-Type: application/json');

use Core\Controllers\AuthController;
use Core\Controllers\ChatController;
use Core\Controllers\GroqController;

AuthController::requireAuthentication();
if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = $_GET['limit'] ?? 10;
    $after = $_GET['after'] ?? null;
    $before = $_GET['before'] ?? null;
    $date = $_GET['id'] ?? date('Y-m-d');

    // Validate limit
    if (!is_numeric($limit) || $limit < 1 || $limit > 10) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid limit parameter (must be between 1 and 10)']);
        exit;
    }

    // Validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD.']);
        exit;
    }

    // Validate timestamp formats
    $datetimeRegex = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(\.\d{1,6})?$/';
    if ($after !== null && !preg_match($datetimeRegex, $after)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid "after" timestamp format. Use YYYY-MM-DD HH:MM:SS.']);
        exit;
    }

    if ($before !== null && !preg_match($datetimeRegex, $before)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid "before" timestamp format. Use YYYY-MM-DD HH:MM:SS.']);
        exit;
    }

    // Logical check: after must be less than before
    if ($after !== null && $before !== null && $after >= $before) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => '"after" must be less than "before".']);
        exit;
    }

    // Fetch chat messages
    try {
        $messages = ChatController::getChat($date, $after, $before, (int)$limit);
        echo json_encode(['success' => true, 'data' => $messages]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
}

// Handle POST request to add a new message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = $_POST['message'];

    if (empty($userMessage)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
        exit;
    }

    // Validate message length
    if (strlen($userMessage) > 1000) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Message is too long (max 1000 characters)']);
        exit;
    }

    $date = date('Y-m-d');

    // Add message to today's chat
    ChatController::addMessage($date, $userMessage, 'user');

    // Fetch chat history for summarization
    try {
        $chatHistory = array_reverse(ChatController::getChat($date, null, null, 15));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Something went wrong while fetching chat history: ' . $e->getMessage()]);
        exit;
    }

    // Prepare history for the responder model
    $history = array_map(fn($m) => [
        'role' => $m['role'],
        'content' => $m['message']
    ], $chatHistory);

    // Call the responder model with the prepared history
    $response = GroqController::callModel('responder_prompts.txt', $history);

    // Check if the response is empty
    if (empty($response)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to generate a response from the AI model']);
        exit;
    }

    // Validate response
    $message = ChatController::addMessage($date, $response, 'assistant');

    // Return the response as JSON
    echo json_encode([
        'success' => true,
        'id' => $message['data']['id'],
        'message' => $response,
    ]);
}
