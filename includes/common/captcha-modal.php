<div class="modal fade" id="captchaModal" tabindex="-1" aria-labelledby="captchaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            --bs-modal-bg: var(--bg-400) !important;
            --bs-modal-color: var(--text-50) !important;
            --bs-modal-header-border-color: var(--stroke-300) !important;
            --bs-modal-footer-border-color: var(--stroke-300) !important;
            --bs-modal-border-color: var(--stroke-300) !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="captchaModalLabel"><?php echo t('captcha_title'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <div id="captcha-error" class="text-danger mb-2" style="display:none;">
                    <?php echo t('captcha_error'); ?>
                </div>
                <div class="g-recaptcha"
                     data-sitekey="<?php echo htmlspecialchars(config['RECAPTCHA_SITE_KEY'] ?? ''); ?>"></div>
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
                    <?php echo t('captcha_button'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

