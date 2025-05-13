function loadHeadAssets({
                            title = "BoostMindAI",
                            description = "Empower your mind with AI.",
                            ogTitle = title,
                            ogDescription = description,
                            ogImage = "assets/img/og-image.jpg"
                        } = {}) {
    const head = document.head;
    const body = document.body;

    // 1. Bootstrap CSS (via CDN)
    const bootstrapLink = document.createElement("link");
    bootstrapLink.rel = "stylesheet";
    bootstrapLink.href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css";
    head.appendChild(bootstrapLink);

    // 2. Feather Icons (via CDN)
    const featherScript = document.createElement("script");
    featherScript.src = "https://unpkg.com/feather-icons";
    featherScript.defer = true;
    head.appendChild(featherScript);

    // 3. Google Fonts: Preconnect
    const preconnect1 = document.createElement("link");
    preconnect1.rel = "preconnect";
    preconnect1.href = "https://fonts.googleapis.com";
    head.appendChild(preconnect1);

    const preconnect2 = document.createElement("link");
    preconnect2.rel = "preconnect";
    preconnect2.href = "https://fonts.gstatic.com";
    preconnect2.crossOrigin = "true";
    head.appendChild(preconnect2);

    // 4. Google Fonts: Inter
    const googleFontsLink = document.createElement("link");
    googleFontsLink.rel = "stylesheet";
    googleFontsLink.href =
        "https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap";
    head.appendChild(googleFontsLink);

    // 5. Favicon
    const faviconLink = document.createElement("link");
    faviconLink.rel = "icon";
    faviconLink.href = "assets/img/favicon.ico";
    head.appendChild(faviconLink);

    // 6. Meta tags
    const metaCharset = document.createElement("meta");
    metaCharset.charset = "UTF-8";
    head.appendChild(metaCharset);

    const metaViewport = document.createElement("meta");
    metaViewport.name = "viewport";
    metaViewport.content = "width=device-width, initial-scale=1.0";
    head.appendChild(metaViewport);

    const metaDescription = document.createElement("meta");
    metaDescription.name = "description";
    metaDescription.content = description;
    head.appendChild(metaDescription);

    // 7. Open Graph Meta Tags
    const ogTitleMeta = document.createElement("meta");
    ogTitleMeta.property = "og:title";
    ogTitleMeta.content = ogTitle;
    head.appendChild(ogTitleMeta);

    const ogDescriptionMeta = document.createElement("meta");
    ogDescriptionMeta.property = "og:description";
    ogDescriptionMeta.content = ogDescription;
    head.appendChild(ogDescriptionMeta);

    const ogImageMeta = document.createElement("meta");
    ogImageMeta.property = "og:image";
    ogImageMeta.content = ogImage;
    head.appendChild(ogImageMeta);

    // 8. Title tag
    const titleTag = document.querySelector("title") || document.createElement("title");
    titleTag.textContent = title;
    head.appendChild(titleTag);

    // 9. Bootstrap JS
    const bootstrapScript = document.createElement("script");
    bootstrapScript.src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js";
    bootstrapScript.defer = true;
    body.appendChild(bootstrapScript);

    // 10. Feather Icons replace
    window.onload = function () {
        if (window.feather) {
            console.log("Feather Icons loaded successfully.");
            feather.replace();
        }
    };
}
