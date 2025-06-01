<?php

namespace Core\Controllers;

use Core\Database;

/**
 * Authentication Controller
 *
 * - Manages session-based user authentication
 * - Supports "remember me" login via persistent token
 * - Provides utilities for checking login status, current user, and logout
 *
 * @package Core\Controllers
 * @author Mustafa Can
 */
class AuthController
{
    /**
     * Initializes the user session and performs auto-login via cookie token.
     *
     * - Starts session if not already started
     * - If session is empty but token cookie exists, validates token and logs user in
     *
     * @return void
     */
    public static function initialize(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is already logged in
        if (!isset($_SESSION['user']) && isset($_COOKIE['boostmindai_token'])) {
            $token = $_COOKIE['boostmindai_token'];
            $db = Database::getConnection();

            // Validate the token from the cookie
            $stmt = $db->prepare('SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW() LIMIT 1');
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $userId = $row['user_id'];

                $stmt2 = $db->prepare('SELECT id, email, name FROM users WHERE id = ?');
                $stmt2->bind_param('i', $userId);
                $stmt2->execute();
                $user = $stmt2->get_result()->fetch_assoc();
                $stmt2->close();

                $_SESSION['user'] = $user;
            }

            $stmt->close();
        }
    }

    /**
     * Returns the currently authenticated user, if any.
     *
     * @return array|null User data or null if not logged in
     */
    public static function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Checks if a user is currently authenticated.
     *
     * @return bool True if user is logged in
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Logs out the current user and clears session and token cookie.
     *
     * @return void
     */
    public static function logout(): void
    {
        session_unset();
        session_destroy();

        // If "Remember Me" token exists, clear it
        if (isset($_COOKIE['boostmindai_token'])) {
            setcookie('boostmindai_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
    }

    /**
     * Ensures the user is authenticated; if not, returns a 401 Unauthorized response.
     *
     * @return void
     */
    public static function requireAuthentication(): void
    {
        if (!self::isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
    }

    public static function getSettings(): array
    {
        $user = self::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Unauthorized'];
        }
        return [
            'success' => true,
            'data' => [
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];
    }

    /**
     * Updates user settings including name and password.
     *
     * @param string $name New name for the user
     * @param string|null $oldPassword Old password for verification (optional)
     * @param string|null $password New password (optional)
     * @return array Result of the update operation
     */
    public static function updateSettings(string $name, ?string $oldPassword, ?string $password): array
    {
        $user = self::getCurrentUser();
        if (!$user) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $db = Database::getConnection();
        $userId = $user['id'];

        // Validate name
        if (strlen($name) < 2 || strlen($name) > 50) {
            return ['success' => false, 'message' => 'Name must be between 2 and 50 characters.'];
        }

        // Validate password
        if ($password && (strlen($password) < 8 || strlen($password) > 32)) {
            return ['success' => false, 'message' => 'Password must be between 8 and 32 characters.'];
        }

        // Validate old password if provided
        if ($password && !$oldPassword) {
            return ['success' => false, 'message' => 'Old password is required to change the password.'];
        }

        // Validate old password if provided
        if ($oldPassword) {
            $stmt = $db->prepare('SELECT password FROM users WHERE id = ?');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row || !password_verify($oldPassword, $row['password'])) {
                return ['success' => false, 'message' => 'Old password is incorrect.'];
            }
        }

        // Update user settings
        $updateQuery = 'UPDATE users SET name = ?';
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery .= ', password = ?';
        }
        $updateQuery .= ' WHERE id = ?';

        $stmt = $db->prepare($updateQuery);
        if ($password) {
            $stmt->bind_param('ssi', $name, $hashedPassword, $userId);
        } else {
            $stmt->bind_param('si', $name, $userId);
        }

        if ($stmt->execute()) {
            $_SESSION['user']['name'] = $name; // Update session data
            return ['success' => true, 'message' => 'Settings updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update settings.'];
        }
    }
}
