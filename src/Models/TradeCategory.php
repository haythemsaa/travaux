<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class TradeCategory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all active trade categories for a language
     */
    public function getAll($language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon,
                parent_category_id,
                display_order
            FROM trade_categories
            WHERE is_active = 1
            ORDER BY display_order ASC, {$nameColumn} ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get category by ID
     */
    public function findById($id, $language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon,
                parent_category_id,
                display_order
            FROM trade_categories
            WHERE id = :id AND is_active = 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get category by slug
     */
    public function findBySlug($slug, $language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon,
                parent_category_id,
                display_order
            FROM trade_categories
            WHERE slug = :slug AND is_active = 1
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get main categories (no parent)
     */
    public function getMainCategories($language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon,
                display_order
            FROM trade_categories
            WHERE parent_category_id IS NULL
            AND is_active = 1
            ORDER BY display_order ASC, {$nameColumn} ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get sub-categories for a parent
     */
    public function getSubCategories($parentId, $language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon,
                display_order
            FROM trade_categories
            WHERE parent_category_id = :parent_id
            AND is_active = 1
            ORDER BY display_order ASC, {$nameColumn} ASC
        ");
        $stmt->execute(['parent_id' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get categories grouped by parent
     */
    public function getCategoriesGrouped($language = 'fr') {
        $mainCategories = $this->getMainCategories($language);

        foreach ($mainCategories as &$category) {
            $category['sub_categories'] = $this->getSubCategories($category['id'], $language);
        }

        return $mainCategories;
    }

    /**
     * Search categories
     */
    public function search($query, $language = 'fr') {
        $nameColumn = "name_" . $language;
        $descColumn = "description_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                slug,
                {$nameColumn} as name,
                {$descColumn} as description,
                icon
            FROM trade_categories
            WHERE ({$nameColumn} LIKE :query OR {$descColumn} LIKE :query OR slug LIKE :query)
            AND is_active = 1
            ORDER BY {$nameColumn} ASC
            LIMIT 20
        ");

        $searchTerm = '%' . $query . '%';
        $stmt->execute(['query' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get popular categories (most used)
     */
    public function getPopular($limit = 12, $language = 'fr') {
        $nameColumn = "name_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                tc.id,
                tc.slug,
                tc.{$nameColumn} as name,
                tc.icon,
                COUNT(p.id) as project_count
            FROM trade_categories tc
            LEFT JOIN projects p ON p.category_id = tc.id
            WHERE tc.is_active = 1
            GROUP BY tc.id
            ORDER BY project_count DESC, tc.{$nameColumn} ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get custom form fields for a category
     */
    public function getCustomFields($categoryId, $language = 'fr') {
        $labelColumn = "label_" . $language;
        $placeholderColumn = "placeholder_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                id,
                field_name,
                field_type,
                {$labelColumn} as label,
                {$placeholderColumn} as placeholder,
                options,
                validation_rules,
                display_order,
                is_required
            FROM custom_form_fields
            WHERE trade_category_id = :category_id
            AND is_active = 1
            ORDER BY display_order ASC
        ");
        $stmt->execute(['category_id' => $categoryId]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Parse JSON fields
        foreach ($fields as &$field) {
            if ($field['options']) {
                $field['options'] = json_decode($field['options'], true);
            }
            if ($field['validation_rules']) {
                $field['validation_rules'] = json_decode($field['validation_rules'], true);
            }
        }

        return $fields;
    }

    /**
     * Check if category has custom fields
     */
    public function hasCustomFields($categoryId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM custom_form_fields
            WHERE trade_category_id = :category_id
            AND is_active = 1
        ");
        $stmt->execute(['category_id' => $categoryId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
