<?php
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

function getSupportedLanguages(): array
{
    return ['en', 'tr', 'de', 'fr'];
}

function loadTranslations($lang = null)
{
    $lang = $lang ?? getPreferredLanguage();
    $file = __DIR__ . '/../../lang/' . $lang . '.php';

    if (file_exists($file)) {
        return include($file);
    }

    // Fallback
    return include(__DIR__ . '/../../lang/en.php');
}

function isDefaultLanguage(): bool
{
    return getPreferredLanguage() === 'en';
}

function getLanguagePrefix(): string
{
    $lang = getPreferredLanguage();
    return $lang === 'en' ? '' : '/' . $lang;
}

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

function t($key)
{
    global $translations;
    return $translations[$key] ?? $key;
}

