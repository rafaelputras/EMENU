<?php
/**
 * Multi-Language Helper
 * Provides the global translate() function for the entire application.
 * Detects language from $_SESSION['lang'] with browser fallback.
 */

function getActiveLanguage() {
    $allowed = ['id', 'en', 'vi'];

    // Priority 1: Session
    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed)) {
        return $_SESSION['lang'];
    }

    // Priority 2: Browser Accept-Language header
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (in_array($browserLang, $allowed)) {
            $_SESSION['lang'] = $browserLang;
            return $browserLang;
        }
    }

    // Default fallback
    $_SESSION['lang'] = 'id';
    return 'id';
}

function translate($key, $fallback = null) {
    static $translations = null;
    static $loadedLang = null;

    $lang = getActiveLanguage();

    // Reload if language changed or first load
    if ($translations === null || $loadedLang !== $lang) {
        $filePath = __DIR__ . '/../Languages/' . $lang . '.php';
        if (file_exists($filePath)) {
            $translations = require $filePath;
        } else {
            $translations = [];
        }
        $loadedLang = $lang;
    }

    return $translations[$key] ?? ($fallback ?? $key);
}

// Shorthand alias
function __($key, $fallback = null) {
    return translate($key, $fallback);
}

/**
 * Format currency to Vietnamese Dong (VND)
 */
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' ₫';
}
