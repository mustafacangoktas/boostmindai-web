<?php

namespace Core;

use mysqli;

/**
 * Database Connection and Initialization Handler
 *
 * - Creates and reuses a singleton mysqli connection
 * - Automatically sets UTF-8 charset
 * - Provides a one-time setup for required tables (users & user_tokens)
 *
 * @package Core
 */
class Database
{
    /**
     * Singleton mysqli connection instance
     * @var mysqli|null
     */
    private static ?mysqli $connection = null;

    /**
     * Returns the active database connection (creates if not exists)
     *
     * @return mysqli
     */
    public static function getConnection(): mysqli
    {
        global $config;
        if (self::$connection === null) {
            self::$connection = new mysqli(
                $config['DB_HOST'],
                $config['DB_USER'],
                $config['DB_PASS'],
                $config['DB_NAME'],
                (int)($config['DB_PORT'] ?? 3306) // Default to 3306 if not set
            );

            if (self::$connection->connect_error) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Database connection failed: ' . self::$connection->connect_error
                ]));
            }

            self::$connection->set_charset('utf8mb4');
        }

        return self::$connection;
    }

    /**
     * Initializes the database schema (users & user_tokens)
     *
     * - This method should be run once during initial setup
     * - Safely creates tables if they don't already exist
     *
     * @return void
     */
    public static function initialize(): void
    {
        $db = self::getConnection();

        // Create 'users' table if it doesn't exist
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

        if (!$db->query($createUsersTableSQL)) {
            die(json_encode([
                'success' => false,
                'message' => 'Failed to create users table: ' . $db->error
            ]));
        }

        // Create 'user_tokens' table for "remember me" functionality
        $createUserTokensTableSQL = <<<SQL
        CREATE TABLE IF NOT EXISTS user_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(64) NOT NULL,
            expires_at DATETIME NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        if (!$db->query($createUserTokensTableSQL)) {
            die(json_encode([
                'success' => false,
                'message' => 'Failed to create user_tokens table: ' . $db->error
            ]));
        }

        // Create 'chat_messages' table for storing user chat history
        $createChatMessagesTableSQL = <<<SQL
        CREATE TABLE IF NOT EXISTS chat_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            message TEXT NOT NULL,
            role ENUM('user', 'assistant') NOT NULL,
            chat_date DATE NOT NULL,
            created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
            INDEX (user_id, chat_date),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        if (!$db->query($createChatMessagesTableSQL)) {
            die(json_encode([
                'success' => false,
                'message' => 'Failed to create chat_messages table: ' . $db->error
            ]));
        }

        // Create 'favorite_messages' table for storing user favorites
        $createFavoriteMessagesTableSQL = <<<SQL
        CREATE TABLE IF NOT EXISTS favorite_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            message_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_favorite (user_id, message_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (message_id) REFERENCES chat_messages(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        if (!$db->query($createFavoriteMessagesTableSQL)) {
            die(json_encode([
                'success' => false,
                'message' => 'Failed to create favorite_messages table: ' . $db->error
            ]));
        }

    }
}
