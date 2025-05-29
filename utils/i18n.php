<?php
/**
 * Get the preferred language based on URL, cookies, or browser settings.
 *
 * @return string The preferred language code (e.g., 'en', 'tr', 'de', 'fr').
 */

function getPreferredLanguage(): string
{
    // First check if language is specified in the URL
    $uri = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($uri, '/'));

    if (!empty($parts[0]) && in_array($parts[0], getSupportedLanguages())) {
        return $parts[0];
    }

    // Then check cookies
    if (isset($_COOKIE['lang'])) {
        return $_COOKIE['lang'];
    }

    // Lastly check browser settings
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    return 'en';
}

/**
 * Get the list of supported languages.
 *
 * @return array An array of supported language codes.
 */
function getSupportedLanguages(): array
{
    return ['en', 'tr', 'de', 'fr'];
}

/**
 * Load translations for the preferred language.
 *
 * @param string|null $lang The language code to load translations for. If null, uses the preferred language.
 * @return array The translations array.
 */
function loadTranslations(?string $lang = null): array
{
    $lang = $lang ?? getPreferredLanguage();
    $file = "lang/{$lang}.php";

    if (file_exists($file)) {
        return include($file);
    }

    // Fallback
    return include("lang/en.php");
}

/**
 * Check if the preferred language is the default language (English).
 *
 * @return bool True if the preferred language is English, false otherwise.
 */
function isDefaultLanguage(): bool
{
    return getPreferredLanguage() === 'en';
}

/**
 * Get the language prefix for URLs.
 *
 * @return string The language prefix (e.g., '/tr', '/de'). Returns an empty string for English.
 */
function getLanguagePrefix(): string
{
    $lang = getPreferredLanguage();
    return $lang === 'en' ? '' : '/' . $lang;
}

/**
 * Remove the language prefix from a given URI.
 *
 * @param string $uri The URI to process.
 * @return string The URI without the language prefix.
 */
function removeLanguagePrefixFromUri(string $uri): string
{
    $parts = explode('/', trim($uri, '/'));

    if (!empty($parts[0]) && in_array($parts[0], getSupportedLanguages())) {
        array_shift($parts);
        return '/' . implode('/', $parts);
    }

    return $uri;
}

$translations = loadTranslations();

/**
 * Translate a given key using the loaded translations.
 *
 * @param string $key The translation key.
 * @return string The translated string, or the key itself if not found.
 */
function t(string $key): string
{
    global $translations;
    return $translations[$key] ?? $key;
}

