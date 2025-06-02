<?php
include_once 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php renderHead(
        t('home_page_title'),
        t('home_page_description'),
        t('home_page_keywords')
    ); ?>
    <link rel="stylesheet" href="/assets/css/home/index.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>
</head>

<body class="body" data-bs-theme="dark">
<?php include 'includes/home/header.php'; ?>
<main>
    <?php include 'includes/home/how-it-works.php'; ?>
    <?php include 'includes/home/testimonial.php'; ?>
    <?php include 'includes/home/privacy-faq.php'; ?>
</main>
<?php include 'includes/common/footer.php'; ?>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>

</html>