<?php

use Core\Controllers\AuthController;
use Core\Services\I18n;

include_once 'includes/common/head.php';

if (AuthController::isAuthenticated()) {
    header('Location: /chat');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo I18n::getPreferredLanguage(); ?>">
<head>
    <?php renderHead(
        t('login_page_title'),
        t('login_page_description'),
        t('login_page_keywords')
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

            <form id="loginForm" novalidate>
                <div class="mb-4 form-floating">
                    <input type="email" id="loginEmail" class="form-control" name="email" placeholder=" " required/>
                    <label for="loginEmail">
                        <?php echo t('login_email'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('login_email_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating position-relative">
                    <input type="password" id="loginPassword" name="password" class="form-control"
                           placeholder="Password" required minlength="8" maxlength="32"/>
                    <label for="loginPassword">
                        <?php echo t('login_password'); ?>
                    </label>
                    <button type="button"
                            class="btn btn-link position-absolute translate-middle-y px-2 show-password-toggle"
                            tabindex="-1" style="z-index: 2; right: 5px; color: var(--text-300);">
                        <i data-feather="eye" class="feather-icon" style="height: 1.37rem;"></i>
                    </button>
                    <div class="invalid-feedback"><?php echo t('login_password_invalid'); ?></div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                    <label class="form-check-label" for="rememberMe">
                        <?php echo t('login_remember_me'); ?>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?php echo t('login_button'); ?>
                    </button>
                </div>

                <p class="text-100 text-center mt-3">
                    <a href="/register" class="text-decoration-none">
                        <?php echo t('login_no_account'); ?> <strong><?php echo t('login_register_link'); ?></strong>
                    </a>
                </p>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.show-password-toggle').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const input = btn.parentElement.querySelector('input[type="password"], input[type="text"]');
                            if (!input) return;
                            if (input.type === 'password') {
                                input.type = 'text';
                                btn.innerHTML = '<i data-feather="eye-off" class="feather-icon" style="height: 1.37rem;"></i>';
                            } else {
                                input.type = 'password';
                                btn.innerHTML = '<i data-feather="eye" class="feather-icon" style="height: 1.37rem;"></i>';
                            }
                            feather.replace();
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function () {
                    const loginForm = document.getElementById('loginForm');
                    const captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
                    let formData = null;

                    loginForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        loginForm.classList.add('was-validated');
                        if (!loginForm.checkValidity()) {
                            return;
                        }
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
                                grecaptcha.reset();
                                captchaModal.hide();
                                if (data.success) {
                                    window.location.href = '/chat';
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
        </div>
    </div>
</main>
<?php include 'includes/common/captcha-modal.php'; ?>
<?php include 'includes/common/footer.php'; ?>
</body>
</html>