<div class="container mt-4">
    <h1 class="mb-3">
        <i class="fas fa-chart-bar"></i> Prix moyens du marché
    </h1>

    <div class="card mb-3">
        <div class="card-body">
            <p class="text-muted">
                Consultez les prix moyens pratiqués par catégorie et région pour estimer vos projets et rester compétitif.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Catégories populaires</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-2">
                <?php foreach ($categories as $category): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-between align-center mb-2">
                                <h4><?= htmlspecialchars($category['name']) ?></h4>
                                <span class="badge badge-primary"><?= $category['project_count'] ?> projets</span>
                            </div>

                            <div class="price-estimator" data-category-id="<?= $category['id'] ?>">
                                <div class="form-group">
                                    <label class="form-label">Ville</label>
                                    <input type="text"
                                           class="form-control city-input"
                                           placeholder="Ex: Paris, Lyon..."
                                           data-category="<?= $category['id'] ?>">
                                </div>

                                <button type="button"
                                        class="btn btn-primary btn-sm w-full get-estimate"
                                        data-category="<?= $category['id'] ?>">
                                    <i class="fas fa-calculator"></i> Obtenir une estimation
                                </button>

                                <div class="estimate-result mt-3" style="display: none;">
                                    <div class="alert alert-success">
                                        <strong>Prix moyen estimé:</strong>
                                        <div class="mt-2" style="font-size: 1.25rem;">
                                            <span class="price-range"></span>
                                        </div>
                                        <small class="text-muted">Basé sur <span class="sample-count"></span> projets similaires</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Conseils pour optimiser vos tarifs</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-3">
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-search-dollar" style="color: var(--primary-color);"></i> Analysez le marché
                    </h4>
                    <p class="text-muted">
                        Comparez régulièrement vos tarifs avec les prix moyens de votre région.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-balance-scale" style="color: var(--secondary-color);"></i> Restez compétitif
                    </h4>
                    <p class="text-muted">
                        Proposez des prix justes et transparents pour maximiser vos chances.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-chart-line" style="color: var(--warning-color);"></i> Ajustez selon la demande
                    </h4>
                    <p class="text-muted">
                        Adaptez vos tarifs en fonction de la saisonnalité et de la demande.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.get-estimate').forEach(button => {
    button.addEventListener('click', function() {
        const categoryId = this.getAttribute('data-category');
        const cityInput = document.querySelector(`.city-input[data-category="${categoryId}"]`);
        const city = cityInput.value;
        const resultDiv = this.parentElement.querySelector('.estimate-result');

        if (!city) {
            alert('Veuillez entrer une ville');
            return;
        }

        // Show loading
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calcul...';

        // Simulate API call (in real app, use fetch)
        setTimeout(() => {
            // Mock data - in real app, fetch from server
            const mockPrice = {
                avg_price_min: 1500 + Math.random() * 1000,
                avg_price_max: 3500 + Math.random() * 2000,
                sample_count: Math.floor(Math.random() * 50) + 10
            };

            const priceRange = resultDiv.querySelector('.price-range');
            const sampleCount = resultDiv.querySelector('.sample-count');

            priceRange.textContent = `${Math.round(mockPrice.avg_price_min).toLocaleString()} € - ${Math.round(mockPrice.avg_price_max).toLocaleString()} €`;
            sampleCount.textContent = mockPrice.sample_count;

            resultDiv.style.display = 'block';
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-redo"></i> Recalculer';
        }, 1000);
    });
});
</script>

<?php $title = 'Prix moyens du marché - Travaux Pro'; ?>
