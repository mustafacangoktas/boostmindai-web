<?php

function renderHead($title, $description, $keywords): void
{
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>';
    echo '<script src="https://unpkg.com/feather-icons" defer></script>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">';
    echo '<link rel="icon" href="/assets/img/branding/favicon.ico">';
    echo '<link rel="stylesheet" href="/assets/css/global.css">';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . $title . '</title>';
    echo '<meta name="description" content="' . $description . '">';
    echo '<meta name="keywords" content="' . $keywords . '">';
    echo '<meta name="author" content="mustafacan.dev">';
    echo '<meta property="og:title" content="' . $title . '">';
    echo '<meta property="og:description" content="' . $description . '">';
    echo '<meta property="og:image" content="/assets/img/branding/favicon.ico">';
    echo '<meta property="og:url" content="https://boostmindai.mustafacan.dev">';
    echo '<meta property="og:type" content="website">';
    echo '<meta property="og:site_name" content="boostmindai.mustafacan.dev">';
    echo '<meta name="twitter:card" content="summary_large_image">';
    echo '<meta name="twitter:title" content="' . $title . '">';
    echo '<meta name="twitter:description" content="' . $description . '">';
    echo '<meta name="twitter:image" content="/assets/img/favicon.ico">';
    echo '<meta name="twitter:site" content="@boostmindai.mustafacan.dev">';
    echo '<meta name="twitter:creator" content="@mustafacan.dev">';
    echo '<script defer>window.onload = function () { if (window.feather) feather.replace(); };</script>';
    // fetch /api/timezone with query parameter "timezone" and set the timezone cookie
    echo '<script defer>fetch("/api/timezone?timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone).catch(error => console.error("Error setting timezone cookie:", error));</script>';
}

