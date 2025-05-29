<?php
/**
 * Logout API Endpoint
 *
 * Clears the user session and deletes any persistent authentication tokens (cookies).
 * Returns a JSON response indicating logout status.
 *
 * @package API
 * @author Mustafa Can Göktaş
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

logout();

http_response_code(200);
echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);