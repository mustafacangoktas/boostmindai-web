<?php
/**
 * Registration API Endpoint
 *
 * Handles new user registration by validating input, checking for existing emails,
 * hashing passwords securely, and storing user data in the database.
 * reCAPTCHA validation is required to prevent abuse.
 *
 * @package API
 * @author Mustafa Can
 */

use Core\Controllers\CaptchaController;
use Core\Database;

CaptchaController::verify();

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '' || $name === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email, name, and password are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}
if (strlen($name) < 2 || strlen($name) > 50) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name must be between 2 and 50 characters.']);
    exit;
}
if (strlen($password) < 8 || strlen($password) > 32) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be between 8 and 32 characters.']);
    exit;
}

$mysqli = Database::getConnection();

$check_stmt = mysqli_prepare($mysqli, 'SELECT id FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($check_stmt, 's', $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_fetch_assoc($check_result)) {
    http_response_code(409);
    echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
    mysqli_stmt_close($check_stmt);
    mysqli_close($mysqli);
    exit;
}

mysqli_stmt_close($check_stmt);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insert_stmt = mysqli_prepare($mysqli, 'INSERT INTO users (email, password, name) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($insert_stmt, 'sss', $email, $hashed_password, $name);

if (mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(['success' => true, 'message' => 'Registration successful.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Registration failed.']);
}

mysqli_stmt_close($insert_stmt);
mysqli_close($mysqli);