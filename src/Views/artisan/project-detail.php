<div class="container mt-4">
    <a href="/artisan/projects" class="btn btn-outline mb-3">
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
                        <span><i class="fas fa-clock"></i> Publié le <?= date('d/m/Y', strtotime($project['created_at'])) ?></span>
                        <span><i class="fas fa-eye"></i> <?= $project['views_count'] ?> vues</span>
                    </div>

                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>

                    <?php if ($project['address']): ?>
                        <h3 class="mt-3">Adresse</h3>
                        <?php if ($hasAccess): ?>
                            <p><?= htmlspecialchars($project['address']) ?></p>
                        <?php else: ?>
                            <p class="text-muted">
                                <i class="fas fa-lock"></i> Adresse disponible après déverrouillage
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

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
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informations client</h3>
                </div>
                <div class="card-body">
                    <?php if ($hasAccess): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Vous avez accès aux coordonnées du client
                        </div>

                        <p><strong>Nom:</strong> <?= htmlspecialchars($project['first_name']) ?> <?= htmlspecialchars($project['last_name']) ?></p>
                        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($project['email']) ?>"><?= htmlspecialchars($project['email']) ?></a></p>
                        <p><strong>Téléphone:</strong> <a href="tel:<?= htmlspecialchars($project['phone']) ?>"><?= htmlspecialchars($project['phone']) ?></a></p>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-lock"></i> Déverrouillez ce projet pour accéder aux coordonnées du client
                        </div>

                        <p class="text-muted">Les coordonnées complètes du client seront disponibles après déverrouillage du projet.</p>

                        <form action="/artisan/projects/<?= $project['id'] ?>/unlock" method="POST">
                            <button type="submit" class="btn btn-primary w-full"
                                    onclick="return confirm('Voulez-vous débloquer ce projet ? (Gratuit pour cette démo)')">
                                <i class="fas fa-unlock"></i> Débloquer les coordonnées
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Soumettre un devis</h3>
                </div>
                <div class="card-body">
                    <?php if ($hasQuoted): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Vous avez déjà soumis un devis pour ce projet
                        </div>
                        <a href="/artisan/dashboard" class="btn btn-outline w-full">Voir mes devis</a>
                    <?php else: ?>
                        <p class="text-muted mb-3">Proposez votre devis pour ce projet</p>
                        <a href="/artisan/projects/<?= $project['id'] ?>/quote" class="btn btn-primary w-full">
                            <i class="fas fa-file-invoice"></i> Créer un devis
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h4>Statistiques du projet</h4>
                    <p><i class="fas fa-file-invoice"></i> <?= $project['quotes_count'] ?> devis reçus</p>
                    <p><i class="fas fa-eye"></i> <?= $project['views_count'] ?> vues</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = htmlspecialchars($project['title']) . ' - Travaux Pro'; ?>
