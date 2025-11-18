<?php

namespace App\Utils;

class i18n {
    private static $language = 'fr';
    private static $translations = [];
    private static $fallbackLanguage = 'en';

    /**
     * Set current language
     */
    public static function setLanguage($lang) {
        self::$language = $lang;
        self::loadTranslations($lang);
    }

    /**
     * Get current language
     */
    public static function getLanguage() {
        return self::$language;
    }

    /**
     * Load translations for a language
     */
    private static function loadTranslations($lang) {
        $file = __DIR__ . '/../../languages/' . $lang . '.php';

        if (file_exists($file)) {
            self::$translations = require $file;
        } else {
            // Load fallback language
            $fallbackFile = __DIR__ . '/../../languages/' . self::$fallbackLanguage . '.php';
            if (file_exists($fallbackFile)) {
                self::$translations = require $fallbackFile;
            }
        }
    }

    /**
     * Translate a key
     * Usage: i18n::t('welcome.message') or i18n::t('user.greeting', ['name' => 'John'])
     */
    public static function t($key, $params = []) {
        $keys = explode('.', $key);
        $value = self::$translations;

        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $key; // Return key if translation not found
            }
        }

        // Replace parameters
        if (is_string($value) && !empty($params)) {
            foreach ($params as $param => $val) {
                $value = str_replace(':' . $param, $val, $value);
            }
        }

        return $value;
    }

    /**
     * Get available languages
     */
    public static function getAvailableLanguages() {
        return [
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
            'en' => ['name' => 'English', 'flag' => '🇬🇧'],
            'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
            'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
            'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
            'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
            'nl' => ['name' => 'Nederlands', 'flag' => '🇳🇱'],
        ];
    }

    /**
     * Format currency based on country
     */
    public static function formatCurrency($amount, $currency = 'EUR') {
        $symbols = [
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            'CHF' => 'CHF',
            'CAD' => 'CA$',
            'AUD' => 'AU$',
            'BRL' => 'R$',
            'MXN' => 'MX$',
        ];

        $symbol = $symbols[$currency] ?? $currency;

        $formatted = number_format($amount, 2, ',', ' ');

        // Currency position based on language
        if (self::$language === 'en') {
            return $symbol . $formatted;
        } else {
            return $formatted . ' ' . $symbol;
        }
    }

    /**
     * Format date based on locale
     */
    public static function formatDate($date, $format = 'medium') {
        $timestamp = is_numeric($date) ? $date : strtotime($date);

        $formats = [
            'fr' => [
                'short' => 'd/m/Y',
                'medium' => 'd/m/Y H:i',
                'long' => 'd F Y à H:i'
            ],
            'en' => [
                'short' => 'm/d/Y',
                'medium' => 'm/d/Y g:i A',
                'long' => 'F d, Y at g:i A'
            ],
            'es' => [
                'short' => 'd/m/Y',
                'medium' => 'd/m/Y H:i',
                'long' => 'd \d\e F \d\e Y, H:i'
            ],
            'de' => [
                'short' => 'd.m.Y',
                'medium' => 'd.m.Y H:i',
                'long' => 'd. F Y, H:i'
            ],
        ];

        $langFormats = $formats[self::$language] ?? $formats['en'];
        $dateFormat = $langFormats[$format] ?? $langFormats['medium'];

        return date($dateFormat, $timestamp);
    }
}

// Helper function for easy translation
if (!function_exists('__')) {
    function __($key, $params = []) {
        return \App\Utils\i18n::t($key, $params);
    }
}
