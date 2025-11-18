<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Country {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all active countries
     */
    public function getAllActive() {
        $stmt = $this->db->prepare("
            SELECT * FROM countries
            WHERE is_active = 1
            ORDER BY name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get country by code
     */
    public function findByCode($code) {
        $stmt = $this->db->prepare("
            SELECT * FROM countries
            WHERE code = :code AND is_active = 1
        ");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get country configuration
     */
    public function getConfig($countryCode) {
        $country = $this->findByCode($countryCode);

        if (!$country) {
            // Return default (France) if country not found
            return $this->findByCode('FR');
        }

        return [
            'code' => $country['code'],
            'name' => $country['name'],
            'language' => $country['language'],
            'currency' => $country['currency_code'],
            'phone_code' => $country['phone_code'],
            'phone_format' => $country['phone_format'],
            'postal_code_format' => $country['postal_code_format'],
            'postal_code_regex' => $country['postal_code_regex'],
            'business_id_label' => $country['business_id_label'],
            'business_id_format' => $country['business_id_format'],
            'tax_rate' => $country['tax_rate'],
            'date_format' => $country['date_format']
        ];
    }

    /**
     * Validate postal code for country
     */
    public function validatePostalCode($postalCode, $countryCode) {
        $country = $this->findByCode($countryCode);

        if (!$country || !$country['postal_code_regex']) {
            return true; // No validation if regex not defined
        }

        return preg_match('/' . $country['postal_code_regex'] . '/', $postalCode);
    }

    /**
     * Get country-specific badges
     */
    public function getBadges($countryCode) {
        $stmt = $this->db->prepare("
            SELECT * FROM country_badges
            WHERE country_code = :country_code
            AND is_active = 1
            ORDER BY display_order ASC
        ");
        $stmt->execute(['country_code' => $countryCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get currency information
     */
    public function getCurrency($currencyCode) {
        $stmt = $this->db->prepare("
            SELECT * FROM currencies
            WHERE code = :code AND is_active = 1
        ");
        $stmt->execute(['code' => $currencyCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Format currency amount
     */
    public function formatCurrency($amount, $currencyCode) {
        $currency = $this->getCurrency($currencyCode);

        if (!$currency) {
            return number_format($amount, 2) . ' ' . $currencyCode;
        }

        $formatted = number_format(
            $amount,
            $currency['decimal_places'],
            $currency['decimal_separator'],
            $currency['thousands_separator']
        );

        if ($currency['symbol_position'] === 'before') {
            return $currency['symbol'] . $formatted;
        } else {
            return $formatted . ' ' . $currency['symbol'];
        }
    }

    /**
     * Convert currency
     */
    public function convertCurrency($amount, $fromCurrency, $toCurrency) {
        $from = $this->getCurrency($fromCurrency);
        $to = $this->getCurrency($toCurrency);

        if (!$from || !$to) {
            return $amount;
        }

        // Convert to USD first, then to target currency
        $amountInUSD = $amount / $from['exchange_rate_to_usd'];
        $convertedAmount = $amountInUSD * $to['exchange_rate_to_usd'];

        return round($convertedAmount, 2);
    }

    /**
     * Get all available currencies
     */
    public function getAllCurrencies() {
        $stmt = $this->db->prepare("
            SELECT * FROM currencies
            WHERE is_active = 1
            ORDER BY code ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
