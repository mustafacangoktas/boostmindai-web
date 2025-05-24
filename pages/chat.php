<?php
include 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php renderHead(
        "BoostMindAI - AI-Powered Motivation Chat",
        "BoostMindAI is an AI-powered platform that provides 
        motivation and support through empathetic conversations.",
        "AI, Motivation, Chat, Support, Empathy"
    ); ?>
    <link rel="stylesheet" href="/assets/css/chat/index.css">
</head>
<body class="body" data-bs-theme="dark">
<div class="glow" aria-hidden="true"></div>
<?php include 'includes/chat/header.php'; ?>
<div class="container-fluid d-flex flex-column flex-md-row gap-4">
    <?php include 'includes/chat/sidebar.php'; ?>
    <main class="d-flex flex-column justify-content-between flex-grow-1">
        <div style="overflow-y: auto; height: 100%;">
            <header class="chat__header">
                <h2 class="fs-6 fw-bold">Today, May 1</h2>
                <div class="d-flex flex-row align-items-center gap-3 mt-4 chat__header-title">
                    <div class="chat__header-avatar">
                        <img src="/assets/img/chat/robot-head.png" alt="Robot Head" height="40">
                    </div>
                    <h1 class="fs-3 fw-bold m-0">Hello! How are you feeling today?</h1>
                </div>
            </header>
            <?php include 'includes/chat/messages.php'; ?>
        </div>
        <?php include 'includes/chat/input.php'; ?>
    </main>
</div>
<?php include 'includes/chat/modals.php'; ?>
<?php include 'includes/chat/scripts.php'; ?>
</body>
</html>
