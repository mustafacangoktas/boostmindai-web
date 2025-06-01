<?php

use Core\Services\I18n;

require_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php renderHead(
        "BoostMindAI - Terms of Service",
        "Read the terms of service for BoostMindAI, including user responsibilities, data privacy, and more.",
        "Terms of Service, BoostMindAI, AI, Motivation, Chat"
    ); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="/assets/css/legal/index.css">
</head>
<body class="body" data-bs-theme="dark">
<div class="glow glow-main" aria-hidden="true"></div>
<?php include 'pages/legal_contents/' . I18n::getPreferredLanguage() . '/terms.php'; ?>
<?php include 'includes/common/footer.php'; ?>
</body>
</html>
