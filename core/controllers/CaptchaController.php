<?php

namespace Core\Controllers;

/**
 * Captcha and Request Validator Controller
 *
 * Handles:
 * - Google reCAPTCHA v2 token verification
 * - HTTP POST method validation
 *
 * If verification fails, it sends an error response and exits.
 *
 * @package Core\Controllers
 */
class CaptchaController
{
    /**
     * Verifies both the reCAPTCHA token and request method.
     *
     * This method:
     * - Validates the Google reCAPTCHA token received from the client
     * - Ensures that the request is made via POST method
     * - Sends appropriate HTTP error responses if validation fails
     *
     * @return void
     */
    public static function verify(): void
    {
        global $config;
        $recaptchaSecret = $config['RECAPTCHA_SECRET_KEY'] ?? '';
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

        // Check for missing captcha response
        if (!$recaptchaResponse) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Captcha is required.'
            ]);
            exit;
        }

        // Verify with Google reCAPTCHA API
        $verify = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($recaptchaSecret) .
            '&response=' . urlencode($recaptchaResponse)
        );
        $captchaSuccess = json_decode($verify);

        // Check for failed validation
        if (!$captchaSuccess || !$captchaSuccess->success) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Captcha verification failed.'
            ]);
            exit;
        }

        // Enforce POST method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
            exit;
        }
    }
}
