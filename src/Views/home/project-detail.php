<div class="container mt-4">
    <a href="/projects" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour aux projets
    </a>

    <div class="grid grid-2">
        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><?= htmlspecialchars($project['title']) ?></h2>
                    <span class="badge badge-primary"><?= htmlspecialchars($project['category_name']) ?></span>
                </div>
                <div class="card-body">
                    <div class="project-meta mb-3">
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($project['city']) ?> (<?= htmlspecialchars($project['postal_code']) ?>)</span>
                        <span><i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($project['created_at'])) ?></span>
                        <span><i class="fas fa-eye"></i> <?= $project['views_count'] ?> vues</span>
                    </div>

                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>

                    <?php if ($project['budget_min'] || $project['budget_max']): ?>
                        <h3 class="mt-3">Budget indicatif</h3>
                        <p>
                            <?php if ($project['budget_min'] && $project['budget_max']): ?>
                                <?= number_format($project['budget_min'], 0, ',', ' ') ?> € -
                                <?= number_format($project['budget_max'], 0, ',', ' ') ?> €
                            <?php elseif ($project['budget_min']): ?>
                                À partir de <?= number_format($project['budget_min'], 0, ',', ' ') ?> €
                            <?php else: ?>
                                Jusqu'à <?= number_format($project['budget_max'], 0, ',', ' ') ?> €
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($project['start_date']): ?>
                        <h3 class="mt-3">Date de début souhaitée</h3>
                        <p><?= date('d/m/Y', strtotime($project['start_date'])) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($photos)): ?>
                        <h3 class="mt-3">Photos</h3>
                        <div class="grid grid-3">
                            <?php foreach ($photos as $photo): ?>
                                <img src="/<?= htmlspecialchars($photo['photo_path']) ?>"
                                     alt="Photo du projet"
                                     style="width: 100%; border-radius: var(--border-radius);">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Intéressé par ce projet ?</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['user_type'] === 'artisan'): ?>
                            <p>Connectez-vous en tant qu'artisan pour soumettre un devis.</p>
                            <a href="/artisan/projects/<?= $project['id'] ?>" class="btn btn-primary w-full">
                                Soumettre un devis
                            </a>
                        <?php else: ?>
                            <p>Ce projet a reçu <?= $project['quotes_count'] ?> devis.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Vous êtes artisan ? Inscrivez-vous pour soumettre un devis.</p>
                        <a href="/register" class="btn btn-primary w-full mb-2">S'inscrire</a>
                        <a href="/login" class="btn btn-outline w-full">Se connecter</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4>Informations</h4>
                    <p><i class="fas fa-user"></i> Client: <?= htmlspecialchars($project['first_name']) ?> <?= substr($project['last_name'], 0, 1) ?>.</p>
                    <p><i class="fas fa-file-invoice"></i> <?= $project['quotes_count'] ?> devis reçus</p>
                    <p><i class="fas fa-eye"></i> <?= $project['views_count'] ?> vues</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = htmlspecialchars($project['title']) . ' - Travaux Pro'; ?>
