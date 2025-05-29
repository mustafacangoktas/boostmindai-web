<?php
/**
 * Login API Endpoint
 *
 * Handles user login by verifying credentials, managing sessions, and optionally
 * generating a persistent token for "Remember Me" functionality.
 * Also includes reCAPTCHA validation to prevent automated abuse.
 *
 * @package API
 * @author Mustafa Can Göktaş
 */

use Random\RandomException;

require_once 'utils/captcha.php';
verify_captcha_and_method();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = $_POST['password'] ?? '';
$rememberMe = isset($_POST['remember_me']);

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

if (strlen($password) < 8 || strlen($password) > 32) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be between 8 and 32 characters.']);
    exit;
}

$stmt = mysqli_prepare(mysqli, 'SELECT id, email, password, name FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    mysqli_stmt_close($stmt);
    mysqli_close(mysqli);
    exit;
}

if (session_status() !== PHP_SESSION_NONE) {
    session_unset();
    session_destroy();
}

if ($rememberMe) {
    try {
        $token = bin2hex(random_bytes(32));
        $userId = $user['id'];
        $expires = time() + (86400 * 30); // 30 days

        $stmt2 = mysqli_prepare(mysqli, 'INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, FROM_UNIXTIME(?))');
        mysqli_stmt_bind_param($stmt2, 'isi', $userId, $token, $expires);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        setcookie('boostmindai_token', $token, [
            'expires' => $expires,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    } catch (RandomException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to generate token.']);
        mysqli_stmt_close($stmt);
        mysqli_close(mysqli);
        exit;
    }
} else {
    ini_set('session.gc_maxlifetime', 1800); // 30 minutes
    session_set_cookie_params([
        'lifetime' => 1800,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

session_start();

$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'name' => $user['name']
];

mysqli_stmt_close($stmt);
mysqli_close(mysqli);

echo json_encode(['success' => true, 'message' => 'Login successful.']);