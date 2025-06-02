<?php

use Core\Controllers\ChatController;

use Core\Controllers\AuthController;
use Core\Services\I18n;

include_once 'includes/common/head.php';

if (!AuthController::isAuthenticated()) {
    // If the user is not authenticated, redirect to the login page
    http_response_code(302);
    header("Location: /login");
    exit;
}

$id = $_GET['id'] ?? null;
$today = date('Y-m-d');

if ($id) {
    if ($id === $today) {
        // If the ID is today, redirect to the chat page for today
        http_response_code(302);
        header("Location: /chat");
        exit;
    }

    $chatExists = false;
    // Check if the chat date exists for the user
    try {
        $chat = ChatController::getChat($id, null, null, 1);
        if ($chat && isset($chat[0])) $chatExists = true;
    } catch (Exception $e) {
    }
    if (!$chatExists) {
        http_response_code(404);
        include 'pages/404.php';
        exit;
    }
}

if (!isset($id)) {
    $title = t('chat_header_title'); // "Hello! How are you feeling today?"
} else {
    $dateObj = DateTime::createFromFormat('Y-m-d', $id);
    $formattedDate = $dateObj ? $dateObj->format('F j, Y') : htmlspecialchars($id);
    $title = t('chat_history_title') . " - " . $formattedDate;
}
include_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="<?php echo I18n::getPreferredLanguage(); ?>">
<head>
    <?php renderHead(
        t('chat_page_title'),
        t('chat_page_description'),
        t('chat_page_keywords')
    ); ?>
    <link rel="stylesheet" href="/assets/css/chat/index.css">
    <link rel="stylesheet" href="/assets/css/auth/index.css">
</head>
<body class="body" data-bs-theme="dark">
<div class="glow glow-main" aria-hidden="true"></div>
<?php include 'includes/chat/header.php'; ?>
<div class="container-fluid d-flex flex-column flex-md-row gap-4">
    <?php include 'includes/chat/sidebar.php'; ?>
    <main class="d-flex flex-column justify-content-between flex-grow-1">
        <div style="overflow-y: auto; height: 100%;">
            <header class="chat__header">
                <h2 class="fs-6 fw-bold" id="chat__header-date">
                </h2>
                <div class="d-flex flex-row align-items-center gap-3 mt-4 chat__header-title">
                    <div class="chat__header-avatar">
                        <img src="/assets/img/chat/robot-head.png" alt="Robot Head" height="40">
                    </div>
                    <h1 class="fs-3 fw-bold m-0">
                        <?php echo htmlspecialchars($title); ?>
                    </h1>
                </div>
            </header>
            <?php include 'includes/chat/messages.php'; ?>
        </div>
        <?php include 'includes/chat/input.php'; ?>
    </main>
</div>
<?php include 'includes/chat/modals.php'; ?>
<?php include 'includes/chat/scripts.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatHeaderDate = document.getElementById('chat__header-date');
        chatHeaderDate.textContent = '<?php if (!isset($id)) echo t('chat_today') . " - " ?>' + new Date(
            '<?php echo $id ? htmlspecialchars($id) : $today; ?>' // Use the provided ID or today's date
        ).toLocaleDateString(undefined, {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
    });
</script>
</body>
</html>
