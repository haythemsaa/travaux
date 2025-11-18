<div class="container mt-4">
    <a href="/client/dashboard" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
    </a>

    <div class="grid grid-2">
        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><?= htmlspecialchars($project['title']) ?></h2>
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
                </div>
                <div class="card-body">
                    <div class="project-meta mb-3">
                        <span><i class="fas fa-tag"></i> <?= htmlspecialchars($project['category_name']) ?></span>
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($project['city']) ?> (<?= htmlspecialchars($project['postal_code']) ?>)</span>
                        <span><i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($project['created_at'])) ?></span>
                    </div>

                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>

                    <?php if ($project['budget_min'] || $project['budget_max']): ?>
                        <h3 class="mt-3">Budget</h3>
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
                    <h3 class="card-title">Devis reçus (<?= count($quotes) ?>)</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($quotes)): ?>
                        <p class="text-center text-muted">Aucun devis reçu pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($quotes as $quote): ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-between align-center mb-2">
                                        <h4><?= htmlspecialchars($quote['company_name']) ?></h4>
                                        <span class="badge badge-<?= $quote['status'] === 'accepted' ? 'success' : 'primary' ?>">
                                            <?php
                                            $quoteStatuses = [
                                                'pending' => 'En attente',
                                                'accepted' => 'Accepté',
                                                'rejected' => 'Refusé',
                                                'expired' => 'Expiré'
                                            ];
                                            echo $quoteStatuses[$quote['status']] ?? $quote['status'];
                                            ?>
                                        </span>
                                    </div>

                                    <p class="text-muted mb-2">
                                        <?= htmlspecialchars($quote['first_name']) ?> <?= htmlspecialchars($quote['last_name']) ?>
                                    </p>

                                    <?php if ($quote['rating_average'] > 0): ?>
                                        <p>
                                            <i class="fas fa-star" style="color: gold;"></i>
                                            <?= number_format($quote['rating_average'], 1) ?>/5
                                            (<?= $quote['total_reviews'] ?> avis)
                                        </p>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <strong>Montant:</strong> <?= number_format($quote['amount'], 2, ',', ' ') ?> €
                                    </div>

                                    <?php if ($quote['estimated_duration']): ?>
                                        <div class="mt-1">
                                            <strong>Durée estimée:</strong> <?= htmlspecialchars($quote['estimated_duration']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                        <strong>Description:</strong>
                                        <p><?= nl2br(htmlspecialchars($quote['description'])) ?></p>
                                    </div>

                                    <?php if ($quote['status'] === 'pending'): ?>
                                        <div class="d-flex gap-2 mt-3">
                                            <form action="/client/quotes/<?= $quote['id'] ?>/accept" method="POST" style="flex: 1;">
                                                <button type="submit" class="btn btn-success w-full"
                                                        onclick="return confirm('Voulez-vous accepter ce devis ?')">
                                                    Accepter
                                                </button>
                                            </form>
                                            <form action="/client/quotes/<?= $quote['id'] ?>/reject" method="POST" style="flex: 1;">
                                                <button type="submit" class="btn btn-danger w-full"
                                                        onclick="return confirm('Voulez-vous refuser ce devis ?')">
                                                    Refuser
                                                </button>
                                            </form>
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
<?php $title = htmlspecialchars($project['title']) . ' - Travaux Pro'; ?>
