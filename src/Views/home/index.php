<section class="hero">
    <div class="container">
        <h1>Trouvez les meilleurs artisans près de chez vous</h1>
        <p>Comparez gratuitement plusieurs devis pour vos travaux</p>
        <div class="d-flex gap-2 justify-center mt-4">
            <a href="/register" class="btn btn-lg btn-secondary">Publier un projet</a>
            <a href="/projects" class="btn btn-lg btn-outline">Voir les projets</a>
        </div>
    </div>
</section>

<section class="container mt-4">
    <h2 class="text-center mb-3">Catégories de travaux</h2>
    <div class="category-grid">
        <?php foreach ($categories as $category): ?>
            <a href="/projects?category=<?= $category['id'] ?>" class="category-item">
                <div class="category-icon">
                    <i class="fas <?= $category['icon'] ?? 'fa-tools' ?>"></i>
                </div>
                <h3><?= htmlspecialchars($category['name']) ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="container mt-4">
    <h2 class="text-center mb-3">Projets récents</h2>
    <div class="grid grid-2">
        <?php foreach ($projects as $project): ?>
            <div class="project-card">
                <div class="project-header">
                    <h3 class="project-title">
                        <a href="/projects/<?= $project['id'] ?>" style="text-decoration: none; color: inherit;">
                            <?= htmlspecialchars($project['title']) ?>
                        </a>
                    </h3>
                    <span class="badge badge-primary"><?= htmlspecialchars($project['category_name']) ?></span>
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
                    <span class="text-muted"><?= $project['quotes_count'] ?> devis reçus</span>
                    <a href="/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">Voir le projet</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <a href="/projects" class="btn btn-primary">Voir tous les projets</a>
    </div>
</section>

<section class="container mt-4 mb-4">
    <div class="card">
        <h2 class="text-center mb-3">Comment ça marche ?</h2>
        <div class="grid grid-3">
            <div class="text-center">
                <div class="category-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>1. Décrivez votre projet</h3>
                <p class="text-muted">Publiez votre projet en quelques minutes avec photos et détails</p>
            </div>
            <div class="text-center">
                <div class="category-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>2. Recevez des devis</h3>
                <p class="text-muted">Des artisans qualifiés vous envoient leurs devis gratuitement</p>
            </div>
            <div class="text-center">
                <div class="category-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>3. Choisissez votre artisan</h3>
                <p class="text-muted">Comparez et choisissez l'artisan qui vous convient le mieux</p>
            </div>
        </div>
    </div>
</section>
<?php $title = 'Accueil - Travaux Pro'; ?>
