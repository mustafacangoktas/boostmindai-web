<?php

use Core\Services\I18n;

require_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(I18n::getPreferredLanguage()); ?>">
<head>
    <?php renderHead(
        t('terms_page_title'),
        t('terms_page_description'),
        t('terms_page_keywords')
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
