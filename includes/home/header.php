<header class="d-flex flex-column home-header">
    <div class="glow glow-main"></div>
    <div class="glow glow-rainbow"></div>
    <?php include "includes/common/navbar.php"; ?>
    <section class="hero container-md">
        <span data-aos="fade-in"
              class="hero__chip btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center justify-content-center">
            <i data-feather="chevrons-up" style="width: 1.1rem;"></i>
            <span class="cursor-default" style="font-size: 0.9rem"><?php echo t('boost_your_mood'); ?></span>
        </span>
        <h1 data-aos="fade-in" class="fs-xxl fw-bold mt-3"
            style="text-wrap: balance"><?php echo t('start_strong_title'); ?></h1>
        <p data-aos="fade-in" class="text-200" style="text-wrap: pretty"><?php echo t('start_strong_desc'); ?></p>
        <div class="position-relative">
            <img data-aos="fade-in" src="/assets/img/home/background.png" alt="Background" class="hero__background user-select-none"
                 height="300"
                 aria-hidden="true">
            <img data-aos="fade-in" class="hero__robot user-select-none" src="/assets/img/home/robot.webp" alt="Robot"
                 height="130"
                 aria-hidden="true">
        </div>
    </section>
</header>

