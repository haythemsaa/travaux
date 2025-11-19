<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Métiers et Formulaires - Travaux Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .trade-card {
            transition: all 0.3s ease;
        }
        .trade-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        .form-preview {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        .form-preview.active {
            max-height: 2000px;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12 mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold mb-3">🔧 Catalogue Complet des Métiers</h1>
            <p class="text-xl text-blue-100">60+ métiers avec formulaires personnalisés et questionnaires détaillés</p>
            <div class="mt-6 flex gap-4 text-sm">
                <span class="bg-blue-500 bg-opacity-30 px-4 py-2 rounded-full">
                    <strong><?php echo count($categories); ?></strong> métiers disponibles
                </span>
                <span class="bg-blue-500 bg-opacity-30 px-4 py-2 rounded-full">
                    <strong>7</strong> langues supportées
                </span>
                <span class="bg-blue-500 bg-opacity-30 px-4 py-2 rounded-full">
                    <strong>Formulaires</strong> dynamiques
                </span>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="max-w-7xl mx-auto px-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" id="searchTrades" placeholder="🔍 Rechercher un métier..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select id="filterCategory" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les catégories</option>
                        <option value="construction">Construction</option>
                        <option value="renovation">Rénovation</option>
                        <option value="amenagement">Aménagement</option>
                        <option value="services">Services</option>
                    </select>
                </div>
                <div>
                    <button onclick="expandAll()" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        📋 Voir tous les formulaires
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trades Grid -->
    <div class="max-w-7xl mx-auto px-4 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="tradesGrid">
            <?php foreach ($categories as $category): ?>
            <div class="trade-card bg-white rounded-lg shadow-md overflow-hidden" data-trade-name="<?php echo strtolower($category['name']); ?>">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="text-4xl mb-3"><?php echo $category['icon'] ?? '🔧'; ?></div>
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($category['name']); ?></h3>
                    <?php if (!empty($category['description'])): ?>
                    <p class="text-blue-100 text-sm"><?php echo htmlspecialchars(substr($category['description'], 0, 80)); ?>...</p>
                    <?php endif; ?>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-600">
                            <?php
                            $fieldCount = count($category['custom_fields'] ?? []);
                            echo $fieldCount > 0 ? "$fieldCount questions" : "Formulaire standard";
                            ?>
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                            ID: <?php echo $category['id']; ?>
                        </span>
                    </div>

                    <?php if (!empty($category['custom_fields'])): ?>
                    <button onclick="toggleForm(<?php echo $category['id']; ?>)"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-lg transition mb-3">
                        <span id="btn-text-<?php echo $category['id']; ?>">📋 Voir le formulaire</span>
                    </button>

                    <!-- Form Preview -->
                    <div id="form-<?php echo $category['id']; ?>" class="form-preview border-t border-gray-200 pt-4">
                        <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Questions personnalisées:</h4>

                        <?php foreach ($category['custom_fields'] as $index => $field): ?>
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <?php echo ($index + 1) . '. ' . htmlspecialchars($field['label']); ?>
                                <?php if ($field['required']): ?>
                                <span class="text-red-500">*</span>
                                <?php endif; ?>
                            </label>

                            <?php if ($field['field_type'] === 'text'): ?>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                       placeholder="<?php echo htmlspecialchars($field['placeholder'] ?? ''); ?>" disabled>

                            <?php elseif ($field['field_type'] === 'textarea'): ?>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                          rows="2" placeholder="<?php echo htmlspecialchars($field['placeholder'] ?? ''); ?>" disabled></textarea>

                            <?php elseif ($field['field_type'] === 'number'): ?>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                       placeholder="<?php echo htmlspecialchars($field['placeholder'] ?? ''); ?>" disabled>

                            <?php elseif ($field['field_type'] === 'select'): ?>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" disabled>
                                    <option>Sélectionner...</option>
                                    <?php if (!empty($field['options'])): ?>
                                        <?php foreach (json_decode($field['options'], true) as $option): ?>
                                        <option><?php echo htmlspecialchars($option); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>

                            <?php elseif ($field['field_type'] === 'radio'): ?>
                                <?php if (!empty($field['options'])): ?>
                                    <?php foreach (json_decode($field['options'], true) as $option): ?>
                                    <label class="flex items-center mb-2">
                                        <input type="radio" class="mr-2" disabled>
                                        <span class="text-sm text-gray-700"><?php echo htmlspecialchars($option); ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php elseif ($field['field_type'] === 'checkbox'): ?>
                                <?php if (!empty($field['options'])): ?>
                                    <?php foreach (json_decode($field['options'], true) as $option): ?>
                                    <label class="flex items-center mb-2">
                                        <input type="checkbox" class="mr-2" disabled>
                                        <span class="text-sm text-gray-700"><?php echo htmlspecialchars($option); ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            <?php elseif ($field['field_type'] === 'date'): ?>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" disabled>

                            <?php endif; ?>

                            <?php if (!empty($field['help_text'])): ?>
                            <p class="mt-2 text-xs text-gray-500 italic">
                                💡 <?php echo htmlspecialchars($field['help_text']); ?>
                            </p>
                            <?php endif; ?>

                            <?php if (!empty($field['validation_rules'])): ?>
                            <p class="mt-1 text-xs text-blue-600">
                                ✓ Validation: <?php echo htmlspecialchars($field['validation_rules']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4 text-gray-500 text-sm">
                        📝 Formulaire standard (pas de questions personnalisées)
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No results message -->
        <div id="noResults" class="hidden text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun métier trouvé</h3>
            <p class="text-gray-500">Essayez avec d'autres mots-clés</p>
        </div>
    </div>

    <!-- Stats Footer -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-4xl font-bold text-blue-400"><?php echo count($categories); ?></div>
                    <div class="text-gray-300 mt-2">Métiers disponibles</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-400">
                        <?php
                        $totalFields = 0;
                        foreach ($categories as $cat) {
                            $totalFields += count($cat['custom_fields'] ?? []);
                        }
                        echo $totalFields;
                        ?>
                    </div>
                    <div class="text-gray-300 mt-2">Questions personnalisées</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-400">7</div>
                    <div class="text-gray-300 mt-2">Langues supportées</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-blue-400">100%</div>
                    <div class="text-gray-300 mt-2">Dynamique</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle form visibility
        function toggleForm(categoryId) {
            const form = document.getElementById('form-' + categoryId);
            const btnText = document.getElementById('btn-text-' + categoryId);

            if (form.classList.contains('active')) {
                form.classList.remove('active');
                btnText.textContent = '📋 Voir le formulaire';
            } else {
                form.classList.add('active');
                btnText.textContent = '✕ Masquer le formulaire';
            }
        }

        // Expand all forms
        function expandAll() {
            const forms = document.querySelectorAll('.form-preview');
            const allExpanded = Array.from(forms).every(f => f.classList.contains('active'));

            forms.forEach((form, index) => {
                if (allExpanded) {
                    form.classList.remove('active');
                    const categoryId = form.id.replace('form-', '');
                    document.getElementById('btn-text-' + categoryId).textContent = '📋 Voir le formulaire';
                } else {
                    form.classList.add('active');
                    const categoryId = form.id.replace('form-', '');
                    document.getElementById('btn-text-' + categoryId).textContent = '✕ Masquer le formulaire';
                }
            });
        }

        // Search functionality
        document.getElementById('searchTrades').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const trades = document.querySelectorAll('.trade-card');
            let visibleCount = 0;

            trades.forEach(trade => {
                const tradeName = trade.getAttribute('data-trade-name');
                if (tradeName.includes(searchTerm)) {
                    trade.style.display = 'block';
                    visibleCount++;
                } else {
                    trade.style.display = 'none';
                }
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        });

        // Category filter
        document.getElementById('filterCategory').addEventListener('change', function(e) {
            const category = e.target.value.toLowerCase();
            const trades = document.querySelectorAll('.trade-card');
            let visibleCount = 0;

            if (!category) {
                trades.forEach(trade => {
                    trade.style.display = 'block';
                    visibleCount++;
                });
            } else {
                trades.forEach(trade => {
                    const tradeName = trade.getAttribute('data-trade-name');
                    // Simple category matching - can be improved with proper categorization
                    if (tradeName.includes(category)) {
                        trade.style.display = 'block';
                        visibleCount++;
                    } else {
                        trade.style.display = 'none';
                    }
                });
            }

            document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        });
    </script>

</body>
</html>
