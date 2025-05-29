<?php
/**
 * Handles user authentication using session and optional "remember me" token.
 *
 * - Starts session if not already started
 * - Checks for existing user session
 * - If not authenticated, validates token from cookie (if present)
 * - If token is valid, loads user data from database and stores it in session
 *
 * @package Utils
 * @author Mustafa Can Göktaş
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) && isset($_COOKIE['boostmindai_token'])) {
    $token = $_COOKIE['boostmindai_token'];

    require_once 'db.php';

    $stmt = mysqli_prepare(mysqli, 'SELECT user_id FROM user_tokens WHERE token = ? AND expires_at > NOW() LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $userId = $row['user_id'];

        $stmt2 = mysqli_prepare(mysqli, 'SELECT id, email, name FROM users WHERE id = ?');
        mysqli_stmt_bind_param($stmt2, 'i', $userId);
        mysqli_stmt_execute($stmt2);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));
        mysqli_stmt_close($stmt2);

        $_SESSION['user'] = $user;
    }

    mysqli_stmt_close($stmt);
}

/**
 * Gets the current authenticated user from the session.
 *
 * @return array|null The user data if authenticated, null otherwise.
 */
function getCurrentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Checks if the user is authenticated.
 *
 * @return bool True if the user is authenticated, false otherwise.
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Logs out the current user.
 *
 * Destroys the session and removes the "boostmindai_token" cookie if it exists.
 *
 * @return void
 */

function logout(): void
{
    session_unset();
    session_destroy();

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
