<div class="container mt-4">
    <a href="/search/artisans" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour aux artisans
    </a>

    <div class="grid grid-2">
        <div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-between align-center">
                        <h2 class="card-title mb-0"><?= htmlspecialchars($artisan['company_name']) ?></h2>
                        <?php if ($this->isClient()): ?>
                            <form action="/favorites/toggle/<?= $artisan['id'] ?>" method="POST" style="display: inline;">
                                <button type="submit" class="btn btn-sm btn-outline" style="border: none; background: none; font-size: 1.5rem;">
                                    <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart" style="color: var(--danger-color);"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php if ($artisan['rating_average'] > 0): ?>
                        <div class="mt-2">
                            <span class="badge badge-warning" style="font-size: 1.1rem;">
                                <i class="fas fa-star"></i> <?= number_format($artisan['rating_average'], 1) ?>/5
                            </span>
                            <span class="text-muted">(<?= $artisan['total_reviews'] ?> avis)</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> <?= htmlspecialchars($artisan['first_name']) ?> <?= htmlspecialchars($artisan['last_name']) ?></p>

                    <?php if ($artisan['city']): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($artisan['city']) ?></p>
                    <?php endif; ?>

                    <?php if ($artisan['years_experience']): ?>
                        <p><i class="fas fa-briefcase"></i> <?= $artisan['years_experience'] ?> ans d'expérience</p>
                    <?php endif; ?>

                    <?php if (!empty($specialties)): ?>
                        <div class="mt-3">
                            <strong>Spécialités:</strong>
                            <div class="mt-2">
                                <?php foreach ($specialties as $specialty): ?>
                                    <span class="badge badge-primary"><?= htmlspecialchars($specialty) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($artisan['description']): ?>
                        <div class="mt-3">
                            <h4>À propos</h4>
                            <p><?= nl2br(htmlspecialchars($artisan['description'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($artisan['website']): ?>
                        <p class="mt-3">
                            <i class="fas fa-globe"></i>
                            <a href="<?= htmlspecialchars($artisan['website']) ?>" target="_blank">
                                <?= htmlspecialchars($artisan['website']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($stats) && $stats['total_reviews'] > 0): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Notes détaillées</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="d-flex justify-between">
                                <span>Qualité des travaux</span>
                                <span><strong><?= number_format($stats['avg_quality'], 1) ?>/5</strong></span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--gray-200); border-radius: 4px;">
                                <div style="width: <?= ($stats['avg_quality'] / 5) * 100 ?>%; background-color: var(--warning-color); border-radius: 4px;"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-between">
                                <span>Ponctualité</span>
                                <span><strong><?= number_format($stats['avg_punctuality'], 1) ?>/5</strong></span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--gray-200); border-radius: 4px;">
                                <div style="width: <?= ($stats['avg_punctuality'] / 5) * 100 ?>%; background-color: var(--warning-color); border-radius: 4px;"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-between">
                                <span>Communication</span>
                                <span><strong><?= number_format($stats['avg_communication'], 1) ?>/5</strong></span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--gray-200); border-radius: 4px;">
                                <div style="width: <?= ($stats['avg_communication'] / 5) * 100 ?>%; background-color: var(--warning-color); border-radius: 4px;"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-between">
                                <span>Rapport qualité/prix</span>
                                <span><strong><?= number_format($stats['avg_price'], 1) ?>/5</strong></span>
                            </div>
                            <div class="progress" style="height: 8px; background-color: var(--gray-200); border-radius: 4px;">
                                <div style="width: <?= ($stats['avg_price'] / 5) * 100 ?>%; background-color: var(--warning-color); border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <?php if (!empty($portfolio)): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Portfolio</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-2">
                            <?php foreach ($portfolio as $item): ?>
                                <div class="card">
                                    <img src="/<?= htmlspecialchars($item['photo_path']) ?>"
                                         alt="<?= htmlspecialchars($item['title']) ?>"
                                         style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--border-radius) var(--border-radius) 0 0;">
                                    <div class="card-body">
                                        <h4 style="font-size: 1rem;"><?= htmlspecialchars($item['title']) ?></h4>
                                        <?php if ($item['category_name']): ?>
                                            <span class="badge badge-primary"><?= htmlspecialchars($item['category_name']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Avis clients (<?= count($reviews) ?>)</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($reviews)): ?>
                        <p class="text-center text-muted">Aucun avis pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-between align-center mb-2">
                                        <strong><?= htmlspecialchars($review['client_name']) ?></strong>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> <?= $review['rating'] ?>/5
                                        </span>
                                    </div>

                                    <?php if ($review['title']): ?>
                                        <h4 style="font-size: 1rem;"><?= htmlspecialchars($review['title']) ?></h4>
                                    <?php endif; ?>

                                    <?php if ($review['comment']): ?>
                                        <p class="text-muted"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    <?php endif; ?>

                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($review['created_at'])) ?>
                                    </small>

                                    <?php if ($review['artisan_response']): ?>
                                        <div class="mt-2" style="padding-left: 1rem; border-left: 3px solid var(--primary-color);">
                                            <strong>Réponse de l'artisan:</strong>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($review['artisan_response'])) ?></p>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($review['response_date'])) ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = htmlspecialchars($artisan['company_name']) . ' - Travaux Pro'; ?>
