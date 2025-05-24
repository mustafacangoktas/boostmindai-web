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
    <link rel="stylesheet" href="/assets/css/home/index.css">
</head>

<body class="body" data-bs-theme="dark">
    <?php include 'includes/home/header.php'; ?>
    <main>
        <?php include 'includes/home/how-it-works.php'; ?>
        <?php include 'includes/home/testimonial.php'; ?>
        <?php include 'includes/home/privacy-faq.php'; ?>
    </main>
    <?php include 'includes/common/footer.php'; ?>
</body>

</html>