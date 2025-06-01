<?php

use Core\Services\I18n;

require_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php renderHead(
        "BoostMindAI - Privacy Policy",
        "Read the privacy policy for BoostMindAI, detailing how we handle your data, cookies, and user rights.",
        "Privacy Policy, BoostMindAI, AI, Motivation, Chat"
    ); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="/assets/css/legal/index.css">
</head>
<body class="body" data-bs-theme="dark">
<div class="glow glow-main" aria-hidden="true"></div>
<?php include 'pages/legal_contents/' . I18n::getPreferredLanguage() . '/privacy.php'; ?>
<?php include 'includes/common/footer.php'; ?>
</body>
</html>
