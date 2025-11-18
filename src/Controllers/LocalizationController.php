<?php

namespace App\Controllers;

use App\Models\Country;
use App\Utils\i18n;

class LocalizationController extends Controller {

    /**
     * Change language
     */
    public function changeLanguage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $language = $_POST['language'] ?? 'fr';

            // Validate language
            $availableLanguages = array_keys(i18n::getAvailableLanguages());

            if (in_array($language, $availableLanguages)) {
                $_SESSION['language'] = $language;
                i18n::setLanguage($language);

                // Redirect back or to home
                $redirect = $_POST['redirect'] ?? '/';
                $this->redirect($redirect);
            }
        }

        $this->redirect('/');
    }

    /**
     * Change country
     */
    public function changeCountry() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $countryCode = $_POST['country_code'] ?? 'FR';

            $countryModel = new Country();
            $country = $countryModel->findByCode($countryCode);

            if ($country) {
                $_SESSION['country_code'] = $countryCode;
                $_SESSION['currency_code'] = $country['currency_code'];

                // Also change language to country's default if not set
                if (!isset($_SESSION['language'])) {
                    $_SESSION['language'] = $country['language'];
                    i18n::setLanguage($country['language']);
                }

                // Redirect back or to home
                $redirect = $_POST['redirect'] ?? '/';
                $this->redirect($redirect);
            }
        }

        $this->redirect('/');
    }

    /**
     * Get countries API endpoint
     */
    public function getCountries() {
        $countryModel = new Country();
        $countries = $countryModel->getAllActive();

        $this->json([
            'success' => true,
            'countries' => $countries
        ]);
    }

    /**
     * Get currencies API endpoint
     */
    public function getCurrencies() {
        $countryModel = new Country();
        $currencies = $countryModel->getAllCurrencies();

        $this->json([
            'success' => true,
            'currencies' => $currencies
        ]);
    }

    /**
     * Currency conversion API
     */
    public function convertCurrency() {
        $amount = $_GET['amount'] ?? 0;
        $from = $_GET['from'] ?? 'EUR';
        $to = $_GET['to'] ?? 'USD';

        $countryModel = new Country();
        $converted = $countryModel->convertCurrency($amount, $from, $to);

        $this->json([
            'success' => true,
            'amount' => $amount,
            'from' => $from,
            'to' => $to,
            'converted' => $converted,
            'formatted' => $countryModel->formatCurrency($converted, $to)
        ]);
    }

    /**
     * Get country configuration
     */
    public function getCountryConfig($countryCode = null) {
        if (!$countryCode) {
            $countryCode = $_SESSION['country_code'] ?? 'FR';
        }

        $countryModel = new Country();
        $config = $countryModel->getConfig($countryCode);
        $badges = $countryModel->getBadges($countryCode);

        $this->json([
            'success' => true,
            'config' => $config,
            'badges' => $badges
        ]);
    }

    /**
     * Validate postal code
     */
    public function validatePostalCode() {
        $postalCode = $_POST['postal_code'] ?? '';
        $countryCode = $_POST['country_code'] ?? $_SESSION['country_code'] ?? 'FR';

        $countryModel = new Country();
        $isValid = $countryModel->validatePostalCode($postalCode, $countryCode);

        $this->json([
            'success' => true,
            'valid' => $isValid
        ]);
    }
}
