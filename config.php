<?php
/**
 * Application Configuration
 * This file contains the configuration settings for the application.
 *
 * Database: MySQL
 * reCAPTCHA: Google reCAPTCHA v2/v3
 */

return [
    // MySQL database connection settings
    'DB_HOST' => 'localhost',         // Database host (e.g., localhost)
    'DB_NAME' => 'your_db_name',      // Database name
    'DB_USER' => 'your_db_user',      // Database username
    'DB_PASS' => 'your_db_password',  // Database password

    // Google reCAPTCHA keys (get from https://www.google.com/recaptcha/admin)
    'RECAPTCHA_SITE_KEY' => 'your_recaptcha_site_key',
    'RECAPTCHA_SECRET_KEY' => 'your_recaptcha_secret_key',
];