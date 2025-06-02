<?php

use Core\Services\I18n;

http_response_code(404);
include_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="<?php echo I18n::getPreferredLanguage(); ?>">
<head>
    <?php renderHead(
        t('not_found_page_title'),
        t('not_found_page_description'),
        t('not_found_page_keywords')
    ); ?>
    <style>
        .error-container {
            text-align: center;
            padding: 2rem 1rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 900;
            color: #ff6b6b;
            letter-spacing: 0.1em;
        }
        .error-message {
            font-size: 1.5rem;
            color: var(--text-200, #fff);
            margin-bottom: 1.5rem;
        }
        .btn-home {
            margin-top: 1rem;
        }
    </style>
</head>
<body class="body" data-bs-theme="dark">
<?php include 'includes/common/navbar.php'; ?>
<div class="glow glow-main" aria-hidden="true"></div>
<div class="error-container mt-10">
    <div class="error-code">404</div>
    <div class="error-message"><?php echo t('404_message') ?: "Aradığınız sayfa bulunamadı."; ?></div>
    <a href="/" class="btn btn-primary btn-home">
        <?php echo t('404_back_home') ?: "Anasayfaya Dön"; ?>
    </a>
</div>
<?php include 'includes/common/footer.php'; ?>
</body>
</html>