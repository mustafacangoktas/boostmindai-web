<footer class="mt-10 mb-3 container-md">
    <div style="background-color: var(--bg-300);" class="px-4 py-3 rounded-3">
        <div class="d-flex flex-column flex-md-row gap-4 justify-content-between align-items-md-center">
            <a class="d-flex align-items-center" href="<?php use Core\Services\I18n;

            echo I18n::getLanguagePrefix(); ?>/">
                <img src="/assets/img/branding/logo.webp" alt="Logo" width="40" height="40" class="me-2">
                <span class="fs-5 fw-bold">BoostMindAI</span>
            </a>
            <ul
                    class="d-flex flex-column flex-md-row gap-3 gap-md-4 m-0 justify-content-center flex-wrap list-unstyled footer__links">
                <li>
                    <a href="<?php echo I18n::getLanguagePrefix(); ?>/privacy"><?php echo t('privacy_policy'); ?></a>
                </li>
                <li>
                    <a href="<?php echo I18n::getLanguagePrefix(); ?>/terms"><?php echo t('terms_of_service'); ?></a>
                </li>
                <li>
                    <a href="<?php echo I18n::getLanguagePrefix(); ?>/cookies"><?php echo t('cookies'); ?></a>
                </li>
            </ul>
        </div>

        <div class="d-flex flex-column-reverse flex-md-row gap-4 justify-content-between mt-4 align-items-md-center">
            <p class="text-md-center text-300 m-0">
                &copy; 2025 BoostMindAI. All rights reserved.
            </p>

            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 language-dropdown-toggle" id="languageDropdown"
                     role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="currentLang">
                        <?php echo I18n::getLanguagePrefix() === 'tr' ? 'Türkçe' : 'English'; ?>
                    </span>
                    <i data-feather="chevron-down" class="language-dropdown-chevron"></i>
                </div>
                <ul class="dropdown-menu language-dropdown-menu" aria-labelledby="languageDropdown">
                    <li><a class="dropdown-item lang-select" href="#" data-lang="en">English</a></li>
                    <li><a class="dropdown-item lang-select" href="#" data-lang="tr">Türkçe</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script>
    const supportedLangs = <?php echo json_encode(I18n::getSupportedLanguages()); ?>;
    document.querySelectorAll('.lang-select').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            document.cookie = 'lang=' + lang + ';path=/;max-age=31536000';

            const currentPath = window.location.pathname;
            const langPrefixRegex = new RegExp('^\\/(' + supportedLangs.join('|') + ')(\\/|$)');
            let cleanPath = currentPath.replace(langPrefixRegex, '/');

            if (lang === 'en') {
                window.location.href = cleanPath;
            } else if (cleanPath === '/' || cleanPath === '') {
                window.location.href = '/' + lang;
            } else {
                window.location.href = '/' + lang + cleanPath;
            }
        });
    });
</script>

