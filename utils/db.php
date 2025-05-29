<?php
/**
 * Database Initialization Script
 *
 * Creates the required tables for user authentication and token management.
 * This script should be executed once during the initial setup of the application.
 *
 * @package utils
 * @author Mustafa Can Göktaş
 */

const mysqli = new mysqli(
    config['DB_HOST'],
    config['DB_USER'],
    config['DB_PASS'],
    config['DB_NAME']
);

if (mysqli_connect_errno()) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . mysqli_connect_error()
    ]));
}

mysqli_set_charset(mysqli, 'utf8mb4');

$createUsersTableSQL = <<<SQL
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(64) NOT NULL,
    name VARCHAR(32) NOT NULL,
    password VARCHAR(256) NOT NULL,
    PRIMARY KEY (id),
    INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

if (!mysqli_query(mysqli, $createUsersTableSQL)) {
    die(json_encode([
        'success' => false,
        'message' => 'Failed to create users table: ' . mysqli_error(mysqli)
    ]));
}

$createUserTokensTableSQL = <<<SQL
CREATE TABLE IF NOT EXISTS user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

if (!mysqli_query(mysqli, $createUserTokensTableSQL)) {
    die(json_encode([
        'success' => false,
        'message' => 'Failed to create user_tokens table: ' . mysqli_error(mysqli)
    ]));
}