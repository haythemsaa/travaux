<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-3">
        <h1>Tableau de bord</h1>
        <a href="/client/projects/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau projet
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_projects'] ?></span>
            <span class="stat-label">Projets publiés</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['active_projects'] ?></span>
            <span class="stat-label">Projets actifs</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_quotes'] ?></span>
            <span class="stat-label">Devis reçus</span>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Mes projets</h2>
        </div>
        <div class="card-body">
            <?php if (empty($projects)): ?>
                <p class="text-center text-muted">Vous n'avez pas encore publié de projet.</p>
                <div class="text-center mt-3">
                    <a href="/client/projects/create" class="btn btn-primary">Publier mon premier projet</a>
                </div>
            <?php else: ?>
                <div class="grid grid-2">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card">
                            <div class="project-header">
                                <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                                <span class="badge badge-<?= $project['status'] === 'published' ? 'success' : 'primary' ?>">
                                    <?php
                                    $statuses = [
                                        'published' => 'Publié',
                                        'in_progress' => 'En cours',
                                        'completed' => 'Terminé',
                                        'cancelled' => 'Annulé'
                                    ];
                                    echo $statuses[$project['status']] ?? $project['status'];
                                    ?>
                                </span>
                                <div class="project-meta">
                                    <span><i class="fas fa-tag"></i> <?= htmlspecialchars($project['category_name']) ?></span>
                                    <span><i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($project['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="project-body">
                                <p class="project-description">
                                    <?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...
                                </p>
                            </div>
                            <div class="project-footer">
                                <span class="badge badge-primary"><?= $project['quotes_count'] ?> devis</span>
                                <a href="/client/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">Voir les détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $title = 'Tableau de bord - Travaux Pro'; ?>
