<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-3">
        <h1>Tableau de bord Artisan</h1>
        <a href="/artisan/projects" class="btn btn-primary">
            <i class="fas fa-search"></i> Trouver des projets
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_quotes'] ?></span>
            <span class="stat-label">Devis envoyés</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['pending_quotes'] ?></span>
            <span class="stat-label">En attente</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['accepted_quotes'] ?></span>
            <span class="stat-label">Acceptés</span>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Mes devis</h2>
            </div>
            <div class="card-body">
                <?php if (empty($quotes)): ?>
                    <p class="text-center text-muted">Vous n'avez pas encore envoyé de devis.</p>
                <?php else: ?>
                    <?php foreach (array_slice($quotes, 0, 5) as $quote): ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center">
                                    <div>
                                        <h4><?= htmlspecialchars($quote['title']) ?></h4>
                                        <p class="text-muted">
                                            <?= htmlspecialchars($quote['city']) ?> -
                                            <?= number_format($quote['amount'], 0, ',', ' ') ?> €
                                        </p>
                                    </div>
                                    <span class="badge badge-<?= $quote['status'] === 'accepted' ? 'success' : ($quote['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                        <?php
                                        $statuses = [
                                            'pending' => 'En attente',
                                            'accepted' => 'Accepté',
                                            'rejected' => 'Refusé',
                                            'expired' => 'Expiré'
                                        ];
                                        echo $statuses[$quote['status']] ?? $quote['status'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Projets récents</h2>
            </div>
            <div class="card-body">
                <?php foreach (array_slice($projects, 0, 5) as $project): ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h4>
                                <a href="/artisan/projects/<?= $project['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($project['title']) ?>
                                </a>
                            </h4>
                            <p class="text-muted">
                                <?= htmlspecialchars($project['city']) ?> -
                                <span class="badge badge-primary"><?= htmlspecialchars($project['category_name']) ?></span>
                            </p>
                            <a href="/artisan/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">
                                Voir le projet
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mt-3">
                    <a href="/artisan/projects" class="btn btn-outline">Voir tous les projets</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = 'Tableau de bord Artisan - Travaux Pro'; ?>
