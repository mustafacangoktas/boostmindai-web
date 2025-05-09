/**
 * Dynamically loads required head assets such as CSS, JS, meta tags, and more.
 *
 * @param {Object} options - Configuration object for setting meta and other assets.
 * @param {string} options.title - Title of the page to set inside <title> tag.
 * @param {string} options.description - Meta description for SEO.
 * @param {string} options.ogTitle - Open Graph title (for social media previews).
 * @param {string} options.ogDescription - Open Graph description (for social media previews).
 * @param {string} options.ogImage - Open Graph image URL for previews.
 */
function loadHeadAssets({
                            title = "BoostMindAI",
                            description = "Empower your mind with AI.",
                            ogTitle = title,
                            ogDescription = description,
                            ogImage = "assets/img/og-image.jpg"  // Replace with your default image
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

    // 3. Google Fonts (Roboto, Open Sans)
    const googleFontsLink = document.createElement("link");
    googleFontsLink.rel = "stylesheet";
    googleFontsLink.href = "https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;600&display=swap";
    head.appendChild(googleFontsLink);

    // 4. Favicon
    const faviconLink = document.createElement("link");
    faviconLink.rel = "icon";
    faviconLink.href = "assets/img/favicon.ico";  // Replace with your actual favicon path
    head.appendChild(faviconLink);

    // 5. Meta tags (SEO)
    const metaDescription = document.createElement("meta");
    metaDescription.name = "description";
    metaDescription.content = description;
    head.appendChild(metaDescription);

    const metaCharset = document.createElement("meta");
    metaCharset.charset = "UTF-8";
    head.appendChild(metaCharset);

    const metaViewport = document.createElement("meta");
    metaViewport.name = "viewport";
    metaViewport.content = "width=device-width, initial-scale=1.0";
    head.appendChild(metaViewport);

    // 6. Open Graph Meta Tags (For social media sharing)
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

    // 7. Title tag
    const titleTag = document.querySelector("title") || document.createElement("title");
    titleTag.textContent = title;
    head.appendChild(titleTag);

    // 8. Bootstrap JS (via CDN) - added at the end of the body
    const bootstrapScript = document.createElement("script");
    bootstrapScript.src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js";
    bootstrapScript.defer = true;
    body.appendChild(bootstrapScript);

    // 9. Initialize Feather Icons after page load
    bootstrapScript.onload = function () {
        if (window.feather) {
            feather.replace();
        }
    };
}
