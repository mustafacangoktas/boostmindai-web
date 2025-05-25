<?php
include 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php renderHead(
        "Login - BoostMindAI",
        "Login to your BoostMindAI account to continue your motivational journey.",
        "Login, Sign In, AI, Motivation, Chat"
    ); ?>
    <link rel="stylesheet" href="/assets/css/auth/index.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="body" data-bs-theme="dark">
<div class="glow glow-main" aria-hidden="true"></div>
<?php include 'includes/common/navbar.php'; ?>
<?php include 'includes/chat/modals.php'; ?>
<main class="container-md mt-8">
    <div class="auth-container">
        <img src="/assets/img/auth/logo.png" alt="Background" class="auth-bg"
             aria-hidden="true">

        <div class="auth-card">
            <h1 class="auth-title">
                <?php echo t('login_title'); ?>
            </h1>
            <p class="text-100 mb-4 mt-3" style="text-align: center; margin-bottom: 2rem; text-wrap: pretty">
                <?php echo t('login_subtitle'); ?>
            </p>
            <form id="loginForm">
                <div class="mb-4 form-floating">
                    <input type="email" name="email" id="loginEmail" class="form-control" placeholder=" "/>
                    <label for="loginEmail">
                        <?php echo t('login_email'); ?>
                    </label>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" name="password" id="loginPassword" class="form-control"
                           placeholder="Password"/>
                    <label for="loginPassword">
                        <?php echo t('login_password'); ?>
                    </label>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input me-2" type="checkbox" value="" id="rememberMe" name="rememberMe"/>
                    <label class="form-check-label" for="rememberMe">
                        <?php echo t('login_remember'); ?>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?php echo t('login_button'); ?>
                    </button>
                </div>
                <div class="mt-3 text-center">
                    <span class="text-200">
                        <?php echo t('login_no_account'); ?>
                        <a href="/register">
                            <?php echo t('login_register_link'); ?>
                        </a></span>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include 'includes/common/captcha-modal.php'; ?>
<?php include 'includes/common/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        const captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
        let formData = null;

        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            formData = new FormData(loginForm);
            captchaModal.show();
        });

        document.getElementById('verifyCaptchaBtn').addEventListener('click', function () {
            const response = grecaptcha.getResponse();
            if (!response) {
                document.getElementById('captcha-error').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('captcha-error').style.display = 'none';
                }, 3000);
                return;
            }
            document.getElementById('captcha-error').style.display = 'none';
            formData.append('g-recaptcha-response', response);
            fetch('/api/auth/login', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    captchaModal.hide();
                    if (data.success) {
                        window.location.href = '/chat.php';
                    } else {
                        alert(data.message || 'Login failed.');
                    }
                })
                .catch(() => {
                    captchaModal.hide();
                    alert('An error occurred.');
                });
        });
    });
</script>
</body>
</html>






