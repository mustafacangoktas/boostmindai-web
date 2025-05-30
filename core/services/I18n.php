<?php

namespace Core\Services;

/**
 * Internationalization (i18n) Service
 *
 * Handles detection and loading of language preferences and translation files.
 *
 * @package Core\Services
 * @author Mustafa Can
 */
class I18n
{
    /** @var array The loaded translations */
    public static array $translations = [];

    /**
     * Detects the preferred language using URI, cookies, or browser headers.
     *
     * @return string Language code (e.g., 'en', 'tr', 'de', 'fr')
     */
    public static function getPreferredLanguage(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $parts = explode('/', trim($uri, '/'));

        // From URI
        if (!empty($parts[0]) && in_array($parts[0], self::getSupportedLanguages())) {
            return $parts[0];
        }

        // From cookie
        if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], self::getSupportedLanguages())) {
            return $_COOKIE['lang'];
        }

        // From browser
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        return 'en';
    }

    /**
     * Returns list of supported languages.
     *
     * @return array Array of supported language codes
     */
    public static function getSupportedLanguages(): array
    {
        return ['en', 'tr', 'de', 'fr'];
    }

    /**
     * Loads translation file for the specified language.
     * Falls back to English if not found.
     *
     * @param string|null $lang Optional language code. Uses preferred if null.
     * @return void
     */
    public static function loadTranslations(?string $lang = null): void
    {
        $lang = $lang ?? self::getPreferredLanguage();
        $file = __DIR__ . "/../../lang/{$lang}.php";

        if (file_exists($file)) {
            self::$translations = include $file;
        } else {
            self::$translations = include __DIR__ . '/../../lang/en.php'; // Fallback to English
        }
    }

    /**
     * Returns whether the current language is the default (English).
     *
     * @return bool True if English, false otherwise
     */
    public static function isDefaultLanguage(): bool
    {
        return self::getPreferredLanguage() === 'en';
    }

    /**
     * Returns the language prefix for URLs.
     *
     * @return string e.g., '/tr', '/de', or empty for English
     */
    public static function getLanguagePrefix(): string
    {
        $lang = self::getPreferredLanguage();
        return $lang === 'en' ? '' : '/' . $lang;
    }

    /**
     * Removes the language prefix from a URI.
     *
     * @param string $uri Full URI path
     * @return string URI without language prefix
     */
    public static function removeLanguagePrefix(string $uri): string
    {
        $parts = explode('/', trim($uri, '/'));

        if (!empty($parts[0]) && in_array($parts[0], self::getSupportedLanguages())) {
            array_shift($parts);
            return '/' . implode('/', $parts);
        }

        return $uri;
    }
}