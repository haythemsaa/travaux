<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class ProjectCustomField {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Save custom field values for a project
     */
    public function saveProjectFields($projectId, $fields) {
        try {
            $this->db->beginTransaction();

            foreach ($fields as $fieldId => $value) {
                // Check if value already exists
                $stmt = $this->db->prepare("
                    SELECT id FROM project_custom_fields
                    WHERE project_id = :project_id AND field_id = :field_id
                ");
                $stmt->execute([
                    'project_id' => $projectId,
                    'field_id' => $fieldId
                ]);

                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                // Convert array values to JSON
                if (is_array($value)) {
                    $value = json_encode($value);
                }

                if ($existing) {
                    // Update
                    $stmt = $this->db->prepare("
                        UPDATE project_custom_fields
                        SET field_value = :value, updated_at = NOW()
                        WHERE id = :id
                    ");
                    $stmt->execute([
                        'value' => $value,
                        'id' => $existing['id']
                    ]);
                } else {
                    // Insert
                    $stmt = $this->db->prepare("
                        INSERT INTO project_custom_fields
                        (project_id, field_id, field_value)
                        VALUES (:project_id, :field_id, :value)
                    ");
                    $stmt->execute([
                        'project_id' => $projectId,
                        'field_id' => $fieldId,
                        'value' => $value
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get custom field values for a project
     */
    public function getProjectFields($projectId, $language = 'fr') {
        $labelColumn = "label_" . $language;

        $stmt = $this->db->prepare("
            SELECT
                pcf.field_id,
                pcf.field_value,
                cff.field_name,
                cff.field_type,
                cff.{$labelColumn} as label
            FROM project_custom_fields pcf
            JOIN custom_form_fields cff ON cff.id = pcf.field_id
            WHERE pcf.project_id = :project_id
        ");
        $stmt->execute(['project_id' => $projectId]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Parse JSON values
        foreach ($fields as &$field) {
            if ($field['field_type'] === 'checkbox' && $field['field_value']) {
                $field['field_value'] = json_decode($field['field_value'], true);
            }
        }

        return $fields;
    }

    /**
     * Get custom fields as key-value array
     */
    public function getProjectFieldsArray($projectId) {
        $stmt = $this->db->prepare("
            SELECT
                cff.field_name,
                pcf.field_value,
                cff.field_type
            FROM project_custom_fields pcf
            JOIN custom_form_fields cff ON cff.id = pcf.field_id
            WHERE pcf.project_id = :project_id
        ");
        $stmt->execute(['project_id' => $projectId]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($fields as $field) {
            $value = $field['field_value'];

            // Parse JSON for checkbox fields
            if ($field['field_type'] === 'checkbox' && $value) {
                $value = json_decode($value, true);
            }

            $result[$field['field_name']] = $value;
        }

        return $result;
    }

    /**
     * Delete custom fields for a project
     */
    public function deleteProjectFields($projectId) {
        $stmt = $this->db->prepare("
            DELETE FROM project_custom_fields
            WHERE project_id = :project_id
        ");
        return $stmt->execute(['project_id' => $projectId]);
    }

    /**
     * Validate custom fields
     */
    public function validateFields($fields, $fieldDefinitions) {
        $errors = [];

        foreach ($fieldDefinitions as $definition) {
            $fieldId = $definition['id'];
            $value = $fields[$fieldId] ?? null;

            // Check required fields
            if ($definition['is_required'] && empty($value)) {
                $errors[$fieldId] = $definition['label'] . ' est requis';
                continue;
            }

            // Validate based on rules
            if (!empty($value) && $definition['validation_rules']) {
                $rules = $definition['validation_rules'];

                // Number validation
                if ($definition['field_type'] === 'number') {
                    if (isset($rules['min']) && $value < $rules['min']) {
                        $errors[$fieldId] = $definition['label'] . ' doit être au moins ' . $rules['min'];
                    }
                    if (isset($rules['max']) && $value > $rules['max']) {
                        $errors[$fieldId] = $definition['label'] . ' ne peut pas dépasser ' . $rules['max'];
                    }
                }

                // Text length validation
                if ($definition['field_type'] === 'text' || $definition['field_type'] === 'textarea') {
                    if (isset($rules['minLength']) && strlen($value) < $rules['minLength']) {
                        $errors[$fieldId] = $definition['label'] . ' doit contenir au moins ' . $rules['minLength'] . ' caractères';
                    }
                    if (isset($rules['maxLength']) && strlen($value) > $rules['maxLength']) {
                        $errors[$fieldId] = $definition['label'] . ' ne peut pas dépasser ' . $rules['maxLength'] . ' caractères';
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Get projects by custom field value
     */
    public function findProjectsByFieldValue($fieldName, $value) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.*
            FROM projects p
            JOIN project_custom_fields pcf ON pcf.project_id = p.id
            JOIN custom_form_fields cff ON cff.id = pcf.field_id
            WHERE cff.field_name = :field_name
            AND pcf.field_value = :value
        ");
        $stmt->execute([
            'field_name' => $fieldName,
            'value' => $value
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
