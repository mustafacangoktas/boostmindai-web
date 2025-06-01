<section data-aos="fade-in"
        class="container-md d-flex position-relative align-items-center gap-6 justify-content-between mt-10 flex-column flex-lg-row">

    <div class="glow glow-accent3"></div>
    <img src="/assets/img/illustration/privacy.svg" alt="Privacy" class="section__image">

    <div>
        <div class="text-center text-md-start">
            <h2 class="fs-3 fw-bold">
                <?php echo t('privacy_title'); ?>
            </h2>
            <p class="text-300 mt-1">
                <?php echo t('privacy_desc'); ?>
            </p>
        </div>

        <div class="privacy-faq__item">
            <div class="d-flex gap-3 mt-3 privacy-faq__card align-items-center justify-content-between">
                <span><?php echo t('faq_store_data_q'); ?></span>
                <i data-feather="chevron-right" class="privacy-faq__icon"></i>
            </div>
            <div class="privacy-faq__answer">
                <p><?php echo t('faq_store_data_a'); ?></p>
            </div>
        </div>

        <div class="privacy-faq__item">
            <div class="d-flex gap-3 mt-3 privacy-faq__card align-items-center justify-content-between">
                <span><?php echo t('faq_delete_history_q'); ?></span>
                <i data-feather="chevron-right" class="privacy-faq__icon"></i>
            </div>
            <div class="privacy-faq__answer">
                <p><?php echo t('faq_delete_history_a'); ?></p>
            </div>
        </div>

        <div class="privacy-faq__item">
            <div class="d-flex gap-3 mt-3 privacy-faq__card align-items-center justify-content-between">
                <span><?php echo t('faq_secure_q'); ?></span>
                <i data-feather="chevron-right" class="privacy-faq__icon"></i>
            </div>
            <div class="privacy-faq__answer">
                <p><?php echo t('faq_secure_a'); ?></p>
            </div>
        </div>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqItems = document.querySelectorAll('.privacy-faq__item');

        faqItems.forEach(item => {
            const card = item.querySelector('.privacy-faq__card');

            card.addEventListener('click', () => {
                item.classList.toggle('active');

                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
            });
        });
    });
</script>