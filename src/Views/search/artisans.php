<div class="container mt-4">
    <h1 class="mb-3">Trouver un artisan</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form action="/search/artisans" method="GET">
                <div class="grid grid-3">
                    <div class="form-group">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="<?= htmlspecialchars($filters['city'] ?? '') ?>"
                               placeholder="Ville">
                    </div>

                    <div class="form-group">
                        <label for="specialty" class="form-label">Spécialité</label>
                        <select id="specialty" name="specialty" class="form-control">
                            <option value="">Toutes les spécialités</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['name']) ?>"
                                        <?= ($filters['specialty'] ?? '') === $category['name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($artisans)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Aucun artisan ne correspond à vos critères.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($artisans as $artisan): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3><?= htmlspecialchars($artisan['company_name']) ?></h3>
                            <?php if ($artisan['rating_average'] > 0): ?>
                                <span class="badge badge-warning">
                                    <i class="fas fa-star"></i> <?= number_format($artisan['rating_average'], 1) ?>/5
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="text-muted mb-2">
                            <?= htmlspecialchars($artisan['first_name']) ?> <?= htmlspecialchars($artisan['last_name']) ?>
                        </p>

                        <?php if ($artisan['city']): ?>
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($artisan['city']) ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($artisan['description']): ?>
                            <p class="text-muted mb-2">
                                <?= htmlspecialchars(substr($artisan['description'], 0, 100)) ?>...
                            </p>
                        <?php endif; ?>

                        <div class="mt-3">
                            <p class="text-muted">
                                <i class="fas fa-star"></i> <?= $artisan['total_reviews'] ?> avis
                                <?php if ($artisan['years_experience']): ?>
                                    | <i class="fas fa-briefcase"></i> <?= $artisan['years_experience'] ?> ans d'expérience
                                <?php endif; ?>
                            </p>
                        </div>

                        <a href="/artisan/<?= $artisan['id'] ?>/profile" class="btn btn-primary btn-sm mt-2">
                            Voir le profil
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $title = 'Trouver un artisan - Travaux Pro'; ?>
