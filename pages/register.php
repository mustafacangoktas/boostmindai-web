<?php
include 'includes/common/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php renderHead(
        "Register - BoostMindAI",
        "Create your BoostMindAI account to start your motivational journey.",
        "Register, Sign Up, AI, Motivation, Chat"
    ); ?>
    <link rel="stylesheet" href="/assets/css/register/index.css">
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
            <h1 class="auth-title">Sign Up</h1>
            <p class="text-100 mb-4 mt-3" style="text-align: center; margin-bottom: 2rem; text-wrap: pretty">Create your BoostMindAI
                account to
                start your motivational journey.</p>
            <form id="registerForm">
                <div class="mb-4 form-floating">
                    <input type="text" id="form3Example1c" class="form-control" placeholder=" "/>
                    <label for="form3Example1c">Full Name</label>
                </div>

                <div class="mb-4 form-floating">
                    <input type="email" id="form3Example3c" class="form-control" placeholder=" "/>
                    <label for="form3Example3c">Email Address</label>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" id="form3Example4c" class="form-control"
                           placeholder="Password (min. 8 characters)"/>
                    <label for="form3Example4c">Password</label>
                </div>

                <div class="mb-4 form-floating">
                    <input type="password" id="form3Example4cd" class="form-control"
                           placeholder="Repeat Password"/>
                    <label for="form3Example4cd">Repeat Password</label>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c"/>
                    <label class="form-check-label" for="form2Example3c">
                        I agree all statements in <a href="#">Terms of service</a>
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>

            </form>
        </div>
    </div>
</main>

<!-- Captcha Verification Modal -->
<div class="modal fade" id="captchaModal" tabindex="-1" aria-labelledby="captchaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            --bs-modal-bg: var(--bg-400) !important;
            --bs-modal-color: var(--text-50) !important;
            --bs-modal-header-border-color: var(--stroke-300) !important;
            --bs-modal-footer-border-color: var(--stroke-300) !important;
            --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="captchaModalLabel">Captcha Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <div id="captcha-error" class="text-danger mb-2" style="display:none;">Please complete the
                    captcha.
                </div>
                <div class="g-recaptcha" data-sitekey="6LdksEcrAAAAAD9Txd8hnLqGOiq2WMNkQxwjnvVb"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" id="verifyCaptchaBtn"
                        style="
            --bs-btn-bg: var(--bg-100) !important;
            --bs-btn-color: var(--text-50) !important;
            --bs-btn-border-color: transparent !important;
            --bs-btn-hover-bg: var(--stroke-100) !important;
            --bs-btn-hover-color: var(--text-50) !important;
            --bs-btn-focus-shadow: 0 0 0 0 rgba(0, 123, 255, 0.25) !important;
            --bs-btn-active-bg: var(--stroke-100) !important;
            --bs-btn-active-color: var(--text-50) !important;
            --bs-btn-active-border-color: transparent !important;
            --bs-btn-focus-bg: var(--stroke-100) !important;
            --bs-btn-focus-color: var(--text-50) !important;
            --bs-btn-focus-border-color: transparent !important;
"
                >
                    Verify Captcha
                </button>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/common/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const registerForm = document.getElementById('registerForm');
        const captchaModal = new bootstrap.Modal(document.getElementById('captchaModal'));
        let formData = null;

        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
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
            fetch('/register-handler.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    captchaModal.hide();
                    if (data.success) {
                        window.location.href = '/chat.php';
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
</body>
</html>
