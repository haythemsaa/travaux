<div class="container mt-4">
    <h1 class="mb-3">Projets disponibles</h1>

    <div class="card mb-3">
        <div class="card-body">
            <form action="/artisan/projects" method="GET">
                <div class="grid grid-4">
                    <div class="form-group">
                        <label for="category" class="form-label">Catégorie</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= ($filters['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="city" class="form-label">Ville</label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="<?= htmlspecialchars($filters['city'] ?? '') ?>"
                               placeholder="Ville">
                    </div>

                    <div class="form-group">
                        <label for="postal_code" class="form-label">Code postal</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control"
                               value="<?= htmlspecialchars($filters['postal_code'] ?? '') ?>"
                               placeholder="75000">
                    </div>

                    <div class="form-group">
                        <label for="urgency" class="form-label">Urgence</label>
                        <select id="urgency" name="urgency" class="form-control">
                            <option value="">Toutes</option>
                            <option value="low" <?= ($filters['urgency'] ?? '') === 'low' ? 'selected' : '' ?>>Basse</option>
                            <option value="medium" <?= ($filters['urgency'] ?? '') === 'medium' ? 'selected' : '' ?>>Moyenne</option>
                            <option value="high" <?= ($filters['urgency'] ?? '') === 'high' ? 'selected' : '' ?>>Haute</option>
                            <option value="urgent" <?= ($filters['urgency'] ?? '') === 'urgent' ? 'selected' : '' ?>>Urgente</option>
                        </select>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <a href="/artisan/projects" class="btn btn-outline">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($projects)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Aucun projet ne correspond à vos critères.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <div class="project-header">
                        <h3 class="project-title">
                            <a href="/artisan/projects/<?= $project['id'] ?>" style="text-decoration: none; color: inherit;">
                                <?= htmlspecialchars($project['title']) ?>
                            </a>
                        </h3>
                        <div class="d-flex gap-1 mb-2">
                            <span class="badge badge-primary"><?= htmlspecialchars($project['category_name']) ?></span>
                            <span class="badge badge-<?= $project['urgency'] === 'urgent' ? 'danger' : 'warning' ?>">
                                <?php
                                $urgencies = [
                                    'low' => 'Basse',
                                    'medium' => 'Moyenne',
                                    'high' => 'Haute',
                                    'urgent' => 'Urgente'
                                ];
                                echo $urgencies[$project['urgency']] ?? $project['urgency'];
                                ?>
                            </span>
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($project['city']) ?></span>
                            <span><i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($project['created_at'])) ?></span>
                        </div>
                    </div>
                    <div class="project-body">
                        <p class="project-description">
                            <?= htmlspecialchars(substr($project['description'], 0, 150)) ?>...
                        </p>
                    </div>
                    <div class="project-footer">
                        <span class="text-muted"><?= $project['quotes_count'] ?> devis</span>
                        <a href="/artisan/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">Voir le projet</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $title = 'Projets disponibles - Travaux Pro'; ?>
