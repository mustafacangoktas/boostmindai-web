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
        t('register_page_title'),
        t('register_page_description'),
        t('register_page_keywords')
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
                <?php echo t('register_title'); ?>
            </h1>
            <p class="text-100 mb-4 mt-3" style="text-align: center; margin-bottom: 2rem; text-wrap: pretty">
                <?php echo t('register_subtitle'); ?>
            </p>

            <form id="registerForm" novalidate>
                <div class="mb-4 form-floating">
                    <input type="text" id="registerName" class="form-control" name="name" placeholder=" " required
                           minlength="2"
                           maxlength="50"/>
                    <label for="registerName">
                        <?php echo t('register_fullname'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('register_name_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating">
                    <input type="email" id="registerEmail" class="form-control" name="email" placeholder=" " required/>
                    <label for="registerEmail">
                        <?php echo t('register_email'); ?>
                    </label>
                    <div class="invalid-feedback"><?php echo t('register_email_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating position-relative">
                    <input type="password" id="registerPassword" name="password" class="form-control"
                           placeholder="Password (min. 8 characters)" required minlength="8" maxlength="32"/>
                    <label for="registerPassword">
                        <?php echo t('register_password'); ?>
                    </label>
                    <button type="button"
                            class="btn btn-link position-absolute top-50 translate-middle-y px-2 show-password-toggle"
                            tabindex="-1" style="z-index: 2; right: 5px; color: var(--text-300);">
                        <i data-feather="eye" class="feather-icon" style="height: 1.3rem;"></i>
                    </button>
                    <div class="invalid-feedback"><?php echo t('register_password_invalid'); ?></div>
                </div>

                <div class="mb-4 form-floating position-relative">
                    <input type="password" id="registerPasswordRepeat" class="form-control"
                           placeholder="Repeat Password" required minlength="8" maxlength="32"/>
                    <label for="registerPasswordRepeat">
                        <?php echo t('register_password_repeat'); ?>
                    </label>
                    <button type="button"
                            class="btn btn-link position-absolute top-50 translate-middle-y px-2 show-password-toggle"
                            tabindex="-1" style="z-index: 2; right: 5px; color: var(--text-300);">
                        <i data-feather="eye" class="feather-icon" style="height: 1.3rem;"></i>
                    </button>
                    <div class="invalid-feedback"><?php echo t('register_password_mismatch'); ?></div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input me-2" type="checkbox" value="" id="registerTerms" required/>
                    <label class="form-check-label" for="registerTerms" style="max-width: 530px;">
                        <span style="margin-bottom: 2px;">
                            <?php echo t('register_terms'); ?>
                        </span>
                        <span style="font-size: 0.97em;">
                            <a href="/terms" target="_blank" style="color: var(--text-200) !important; text-decoration: underline; white-space: nowrap;">
                                <?php echo t('register_terms_link'); ?>,
                            </a>
                            <a href="/privacy" target="_blank" style="color: var(--text-200) !important; text-decoration: underline; white-space: nowrap;">
                                <?php echo t('privacy_policy'); ?>,
                            </a>
                            <a href="/cookie-policy" target="_blank" style="color: var(--text-200) !important; text-decoration: underline; white-space: nowrap;">
                                <?php echo t('cookies'); ?>
                            </a>
                        </span>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <?php echo t('register_button'); ?>
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.show-password-toggle').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const input = btn.parentElement.querySelector('input[type="password"], input[type="text"]');
                            if (!input) return;
                            if (input.type === 'password') {
                                input.type = 'text';
                                btn.innerHTML = '<i data-feather="eye-off" class="feather-icon" style="height: 1.3rem;"></i>';
                            } else {
                                input.type = 'password';
                                btn.innerHTML = '<i data-feather="eye" class="feather-icon" style="height: 1.3rem;"></i>';
                            }
                            feather.replace();
                        });
                    });
                });
                document.addEventListener('DOMContentLoaded', function () {
                    const registerForm = document.getElementById('registerForm');
                    const captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
                    let formData = null;

                    registerForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        let valid = true;
                        registerForm.classList.add('was-validated');

                        // Password match check
                        const password = document.getElementById('registerPassword');
                        const repeatPassword = document.getElementById('registerPasswordRepeat');
                        if (password.value !== repeatPassword.value) {
                            repeatPassword.setCustomValidity("<?php echo t('register_password_mismatch'); ?>");
                            valid = false;
                        } else {
                            repeatPassword.setCustomValidity('');
                        }

                        if (!registerForm.checkValidity()) {
                            valid = false;
                        }

                        if (!valid) {
                            return;
                        }

                        formData = new FormData(registerForm);
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
                        fetch('/api/auth/register', {
                            method: 'POST',
                            body: formData
                        })
                            .then(res => res.json())
                            .then(data => {
                                captchaModal.hide();
                                if (data.success) {
                                    window.location.href = '/login';
                                } else {
                                    alert(data.message || 'Registration failed.');
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
