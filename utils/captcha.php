<?php
/**
 * reCAPTCHA and Request Method Verifier
 *
 * This script validates the reCAPTCHA token and ensures that the incoming
 * HTTP request method is POST. It is intended to be included or required
 * in other scripts that require basic request validation and bot protection.
 *
 * If the validation fails, it returns an HTTP error response and halts execution.
 *
 * @package utils
 * @author Mustafa Can Göktaş
 */

require_once 'db.php';

function verify_captcha_and_method(): void
{
    $recaptchaSecret = config['RECAPTCHA_SECRET_KEY'] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (!$recaptchaResponse) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Captcha is required.']);
        exit;
    }

    $verify = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($recaptchaSecret) .
        '&response=' . urlencode($recaptchaResponse)
    );
    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess || !$captchaSuccess->success) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Captcha verification failed.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        exit;
    }
}