<nav class="navbar d-flex justify-content-between align-items-center py-3">
    <div class="container-md">
        <a class="d-flex align-items-center" href="<?php echo getLanguagePrefix(); ?>/">
            <img src="/assets/img/branding/logo.webp" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-5 fw-bold d-none d-md-inline"><?php echo t('navbar_brand'); ?></span>
        </a>
        <button onclick="window.location.href = '<?php echo getLanguagePrefix(); ?>/chat';"
                class="btn btn-outline-secondary rounded px-3 py-2 d-flex align-items-center justify-content-center gap-1"
                style="--bs-btn-bg: var(--bg-200);
            --bs-btn-color: var(--text-50);
            --bs-btn-border-color: var(--stroke-100);
            --bs-btn-hover-bg: var(--stroke-100);
            --bs-btn-hover-color: var(--text-50);
            --bs-btn-hover-border-color: var(--stroke-100);
            --bs-btn-active-bg: var(--stroke-200);
            --bs-btn-active-color: var(--text-50);
            --bs-btn-active-border-color: var(--stroke-200);
">
            <i data-feather="message-circle"></i> <span><?php echo t('navbar_start_chat'); ?></span>
        </button>
    </div>
</nav>

