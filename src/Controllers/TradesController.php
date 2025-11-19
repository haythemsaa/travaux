<?php

namespace App\Controllers;

use App\Models\TradeCategory;
use App\Models\ProjectCustomField;

class TradesController {

    private $tradeCategoryModel;
    private $customFieldModel;

    public function __construct() {
        $this->tradeCategoryModel = new TradeCategory();
        $this->customFieldModel = new ProjectCustomField();
    }

    /**
     * Display all trades with their custom forms
     */
    public function index() {
        $language = $_SESSION['language'] ?? 'fr';

        // Get all categories with translations
        $categories = $this->tradeCategoryModel->getAllWithTranslations($language);

        // Get custom fields for each category
        foreach ($categories as &$category) {
            $category['custom_fields'] = $this->customFieldModel->getFieldsByCategory($category['id']);
        }

        require_once __DIR__ . '/../Views/trades/index.php';
    }

    /**
     * Display single trade category with form preview
     */
    public function show($id) {
        $language = $_SESSION['language'] ?? 'fr';

        $category = $this->tradeCategoryModel->findById($id, $language);

        if (!$category) {
            header('HTTP/1.0 404 Not Found');
            echo "Trade category not found";
            return;
        }

        $category['custom_fields'] = $this->customFieldModel->getFieldsByCategory($id);

        require_once __DIR__ . '/../Views/trades/show.php';
    }

    /**
     * Get form fields as JSON for AJAX
     */
    public function getFields($categoryId) {
        header('Content-Type: application/json');

        $fields = $this->customFieldModel->getFieldsByCategory($categoryId);

        echo json_encode([
            'success' => true,
            'fields' => $fields
        ]);
    }
}
