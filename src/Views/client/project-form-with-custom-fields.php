<div class="container mt-4">
    <h1><?= __('project.create') ?></h1>

    <?php
    use App\Utils\i18n;

    $language = $_SESSION['language'] ?? 'fr';
    $countryCode = $_SESSION['country_code'] ?? 'FR';

    // Get country configuration
    $countryModel = new \App\Models\Country();
    $countryConfig = $countryModel->getConfig($countryCode);

    // Get trade categories
    $tradeCategoryModel = new \App\Models\TradeCategory();
    $categories = $tradeCategoryModel->getAll($language);
    ?>

    <form action="/client/projects/create" method="POST" enctype="multipart/form-data" id="projectForm">
        <!-- Basic Project Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h3><?= __('project.basic_info') ?? 'Informations de base' ?></h3>
            </div>
            <div class="card-body">
                <!-- Country -->
                <div class="form-group">
                    <label for="country_code"><?= __('common.country') ?? 'Pays' ?> *</label>
                    <select name="country_code" id="country_code" class="form-control" required>
                        <?php foreach ($countryModel->getAllActive() as $country): ?>
                            <option value="<?= $country['code'] ?>"
                                <?= $country['code'] === $countryCode ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Project Title -->
                <div class="form-group">
                    <label for="title"><?= __('project.project_title') ?> *</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <!-- Category Selection -->
                <div class="form-group">
                    <label for="category_id"><?= __('project.category') ?> *</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value=""><?= __('common.select') ?? 'Sélectionner' ?>...</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" data-slug="<?= $category['slug'] ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description"><?= __('project.description') ?> *</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Budget -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="budget_min"><?= __('project.budget_min') ?></label>
                            <div class="input-group">
                                <input type="number" name="budget_min" id="budget_min" class="form-control">
                                <span class="input-group-text"><?= $countryConfig['currency'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="budget_max"><?= __('project.budget_max') ?></label>
                            <div class="input-group">
                                <input type="number" name="budget_max" id="budget_max" class="form-control">
                                <span class="input-group-text"><?= $countryConfig['currency'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address"><?= __('project.address') ?> *</label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="postal_code"><?= __('project.postal_code') ?> *</label>
                            <input type="text"
                                   name="postal_code"
                                   id="postal_code"
                                   class="form-control"
                                   placeholder="<?= $countryConfig['postal_code_format'] ?>"
                                   required>
                            <small class="form-text text-muted">
                                <?= __('common.format') ?? 'Format' ?>: <?= $countryConfig['postal_code_format'] ?>
                            </small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="city"><?= __('project.city') ?> *</label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Preferred Date -->
                <div class="form-group">
                    <label for="preferred_date"><?= __('project.preferred_date') ?></label>
                    <input type="date" name="preferred_date" id="preferred_date" class="form-control">
                </div>

                <!-- Urgency -->
                <div class="form-group">
                    <label for="urgency"><?= __('project.urgency') ?></label>
                    <select name="urgency" id="urgency" class="form-control">
                        <option value="low"><?= __('project.urgency_low') ?? 'Basse' ?></option>
                        <option value="normal" selected><?= __('project.urgency_normal') ?? 'Normale' ?></option>
                        <option value="high"><?= __('project.urgency_high') ?? 'Haute' ?></option>
                        <option value="urgent"><?= __('project.urgency_urgent') ?? 'Urgente' ?></option>
                    </select>
                </div>

                <!-- Photos -->
                <div class="form-group">
                    <label for="photos"><?= __('project.photos') ?> (<?= __('common.max') ?? 'max' ?> 5)</label>
                    <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
                </div>
            </div>
        </div>

        <!-- Custom Fields for Selected Trade -->
        <div class="card mb-3" id="customFieldsCard" style="display: none;">
            <div class="card-header">
                <h3><?= __('project.specific_details') ?? 'Détails spécifiques' ?></h3>
            </div>
            <div class="card-body" id="customFieldsContainer">
                <!-- Custom fields will be loaded here via AJAX -->
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-check"></i> <?= __('project.create') ?>
            </button>
            <a href="/client/dashboard" class="btn btn-outline ms-2">
                <?= __('common.cancel') ?>
            </a>
        </div>
    </form>
</div>

<script>
// Load custom fields when category changes
document.getElementById('category_id').addEventListener('change', async function() {
    const categoryId = this.value;
    const customFieldsCard = document.getElementById('customFieldsCard');
    const customFieldsContainer = document.getElementById('customFieldsContainer');

    if (!categoryId) {
        customFieldsCard.style.display = 'none';
        return;
    }

    // Show loading
    customFieldsContainer.innerHTML = '<p class="text-muted">Loading...</p>';
    customFieldsCard.style.display = 'block';

    try {
        // Fetch custom fields for this category
        const response = await fetch(`/api/categories/${categoryId}/fields`);
        const data = await response.json();

        if (data.fields && data.fields.length > 0) {
            renderCustomFields(data.fields);
        } else {
            customFieldsCard.style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading custom fields:', error);
        customFieldsContainer.innerHTML = '<p class="text-danger">Error loading fields</p>';
    }
});

function renderCustomFields(fields) {
    const container = document.getElementById('customFieldsContainer');
    let html = '';

    fields.forEach(field => {
        html += renderField(field);
    });

    container.innerHTML = html;
}

function renderField(field) {
    const required = field.is_required ? 'required' : '';
    const requiredLabel = field.is_required ? ' *' : '';

    switch (field.field_type) {
        case 'text':
            return `
                <div class="form-group">
                    <label for="custom_${field.id}">${field.label}${requiredLabel}</label>
                    <input type="text"
                           name="custom_fields[${field.id}]"
                           id="custom_${field.id}"
                           class="form-control"
                           placeholder="${field.placeholder || ''}"
                           ${required}>
                </div>
            `;

        case 'number':
            const min = field.validation_rules?.min || '';
            const max = field.validation_rules?.max || '';
            return `
                <div class="form-group">
                    <label for="custom_${field.id}">${field.label}${requiredLabel}</label>
                    <input type="number"
                           name="custom_fields[${field.id}]"
                           id="custom_${field.id}"
                           class="form-control"
                           placeholder="${field.placeholder || ''}"
                           ${min ? `min="${min}"` : ''}
                           ${max ? `max="${max}"` : ''}
                           ${required}>
                </div>
            `;

        case 'textarea':
            return `
                <div class="form-group">
                    <label for="custom_${field.id}">${field.label}${requiredLabel}</label>
                    <textarea name="custom_fields[${field.id}]"
                              id="custom_${field.id}"
                              class="form-control"
                              rows="3"
                              placeholder="${field.placeholder || ''}"
                              ${required}></textarea>
                </div>
            `;

        case 'select':
            let options = '<option value="">Sélectionner...</option>';
            if (field.options && field.options.options) {
                field.options.options.forEach(opt => {
                    options += `<option value="${opt}">${opt}</option>`;
                });
            }
            return `
                <div class="form-group">
                    <label for="custom_${field.id}">${field.label}${requiredLabel}</label>
                    <select name="custom_fields[${field.id}]"
                            id="custom_${field.id}"
                            class="form-control"
                            ${required}>
                        ${options}
                    </select>
                </div>
            `;

        case 'radio':
            let radios = '';
            if (field.options && field.options.options) {
                field.options.options.forEach((opt, index) => {
                    radios += `
                        <div class="form-check">
                            <input type="radio"
                                   name="custom_fields[${field.id}]"
                                   id="custom_${field.id}_${index}"
                                   value="${opt}"
                                   class="form-check-input"
                                   ${required}>
                            <label class="form-check-label" for="custom_${field.id}_${index}">
                                ${opt}
                            </label>
                        </div>
                    `;
                });
            }
            return `
                <div class="form-group">
                    <label>${field.label}${requiredLabel}</label>
                    ${radios}
                </div>
            `;

        case 'checkbox':
            let checkboxes = '';
            if (field.options && field.options.options) {
                field.options.options.forEach((opt, index) => {
                    checkboxes += `
                        <div class="form-check">
                            <input type="checkbox"
                                   name="custom_fields[${field.id}][]"
                                   id="custom_${field.id}_${index}"
                                   value="${opt}"
                                   class="form-check-input">
                            <label class="form-check-label" for="custom_${field.id}_${index}">
                                ${opt}
                            </label>
                        </div>
                    `;
                });
            } else {
                checkboxes = `
                    <div class="form-check">
                        <input type="checkbox"
                               name="custom_fields[${field.id}]"
                               id="custom_${field.id}"
                               value="1"
                               class="form-check-input">
                        <label class="form-check-label" for="custom_${field.id}">
                            ${field.label}
                        </label>
                    </div>
                `;
            }
            return `
                <div class="form-group">
                    ${field.options && field.options.options ? `<label>${field.label}${requiredLabel}</label>` : ''}
                    ${checkboxes}
                </div>
            `;

        case 'date':
            return `
                <div class="form-group">
                    <label for="custom_${field.id}">${field.label}${requiredLabel}</label>
                    <input type="date"
                           name="custom_fields[${field.id}]"
                           id="custom_${field.id}"
                           class="form-control"
                           ${required}>
                </div>
            `;

        default:
            return '';
    }
}

// Postal code validation
document.getElementById('postal_code').addEventListener('blur', async function() {
    const postalCode = this.value;
    const countryCode = document.getElementById('country_code').value;

    if (!postalCode) return;

    try {
        const response = await fetch('/localization/validate-postal-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `postal_code=${postalCode}&country_code=${countryCode}`
        });

        const data = await response.json();

        if (!data.valid) {
            this.classList.add('is-invalid');
            this.setCustomValidity('Invalid postal code format');
        } else {
            this.classList.remove('is-invalid');
            this.setCustomValidity('');
        }
    } catch (error) {
        console.error('Error validating postal code:', error);
    }
});
</script>

<?php $title = __('project.create') . ' - Travaux Pro'; ?>
