<div class="container mt-4">
    <a href="/client/projects/<?= $project['id'] ?>" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour au projet
    </a>

    <div class="d-flex justify-between align-center mb-3">
        <h1>
            <i class="fas fa-balance-scale"></i> Comparaison des devis
        </h1>
        <a href="/quotes/export-pdf/<?= $project['id'] ?>" class="btn btn-outline">
            <i class="fas fa-file-pdf"></i> Exporter en PDF
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title"><?= htmlspecialchars($project['title']) ?></h3>
        </div>
        <div class="card-body">
            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
                <div class="stat-card">
                    <span class="stat-number"><?= $comparison['total_quotes'] ?></span>
                    <span class="stat-label">Devis reçus</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= number_format($comparison['lowest_price'], 0, ',', ' ') ?> €</span>
                    <span class="stat-label">Prix le plus bas</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= number_format($comparison['average_price'], 0, ',', ' ') ?> €</span>
                    <span class="stat-label">Prix moyen</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= number_format($comparison['best_rated'], 1) ?>/5</span>
                    <span class="stat-label">Meilleure note</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tableau comparatif</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: var(--gray-100);">
                        <th style="padding: 1rem; text-align: left;">Artisan</th>
                        <th style="padding: 1rem; text-align: center;">Note</th>
                        <th style="padding: 1rem; text-align: right;">Montant</th>
                        <th style="padding: 1rem; text-align: center;">Durée estimée</th>
                        <th style="padding: 1rem; text-align: center;">Date de début</th>
                        <th style="padding: 1rem; text-align: center;">Statut</th>
                        <th style="padding: 1rem; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $quote): ?>
                        <?php
                        $isLowest = $quote['amount'] == $comparison['lowest_price'];
                        $isBestRated = $quote['rating_average'] == $comparison['best_rated'];
                        $rowStyle = '';
                        if ($quote['status'] === 'accepted') {
                            $rowStyle = 'background-color: #d1fae5;';
                        }
                        ?>
                        <tr style="border-bottom: 1px solid var(--gray-200); <?= $rowStyle ?>">
                            <td style="padding: 1rem;">
                                <div>
                                    <strong><?= htmlspecialchars($quote['company_name']) ?></strong>
                                    <?php if ($isBestRated): ?>
                                        <span class="badge badge-warning" style="font-size: 0.75rem;">
                                            <i class="fas fa-trophy"></i> Mieux noté
                                        </span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($quote['first_name']) ?> <?= htmlspecialchars($quote['last_name']) ?>
                                    </small>
                                </div>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?php if ($quote['rating_average'] > 0): ?>
                                    <div>
                                        <i class="fas fa-star" style="color: gold;"></i>
                                        <strong><?= number_format($quote['rating_average'], 1) ?></strong>/5
                                    </div>
                                    <small class="text-muted">(<?= $quote['total_reviews'] ?> avis)</small>
                                <?php else: ?>
                                    <span class="text-muted">Pas d'avis</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; text-align: right;">
                                <strong style="font-size: 1.25rem; color: var(--primary-color);">
                                    <?= number_format($quote['amount'], 2, ',', ' ') ?> €
                                </strong>
                                <?php if ($isLowest): ?>
                                    <br>
                                    <span class="badge badge-success" style="font-size: 0.75rem;">
                                        <i class="fas fa-tag"></i> Prix le plus bas
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?= htmlspecialchars($quote['estimated_duration'] ?? 'Non spécifié') ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?= $quote['start_date'] ? date('d/m/Y', strtotime($quote['start_date'])) : 'À définir' ?>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <?php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'accepted' => 'success',
                                    'rejected' => 'danger',
                                    'expired' => 'gray'
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'accepted' => 'Accepté',
                                    'rejected' => 'Refusé',
                                    'expired' => 'Expiré'
                                ];
                                $color = $statusColors[$quote['status']] ?? 'primary';
                                $label = $statusLabels[$quote['status']] ?? $quote['status'];
                                ?>
                                <span class="badge badge-<?= $color ?>"><?= $label ?></span>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="/quotes/pdf/<?= $quote['id'] ?>" class="btn btn-sm btn-outline" title="Télécharger PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <?php if ($quote['status'] === 'pending'): ?>
                                    <a href="/client/projects/<?= $project['id'] ?>" class="btn btn-sm btn-primary">
                                        Voir
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-lightbulb"></i> Notre recommandation
            </h3>
        </div>
        <div class="card-body">
            <?php
            // Simple recommendation algorithm
            $recommended = null;
            $bestScore = -1;

            foreach ($quotes as $quote) {
                $score = 0;
                // Price score (inverse - lower is better)
                $priceScore = $comparison['highest_price'] > 0 ?
                    (1 - ($quote['amount'] / $comparison['highest_price'])) * 40 : 0;
                $score += $priceScore;

                // Rating score
                $ratingScore = ($quote['rating_average'] ?? 0) * 8; // Max 40 points
                $score += $ratingScore;

                // Review count score
                $reviewScore = min(($quote['total_reviews'] ?? 0) / 2, 20); // Max 20 points
                $score += $reviewScore;

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $recommended = $quote;
                }
            }
            ?>

            <?php if ($recommended): ?>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 2rem; border-radius: var(--border-radius);">
                    <h4 style="color: white;">
                        <i class="fas fa-award"></i> Nous vous recommandons: <?= htmlspecialchars($recommended['company_name']) ?>
                    </h4>
                    <div class="grid grid-3 mt-3">
                        <div>
                            <strong>Prix:</strong> <?= number_format($recommended['amount'], 0, ',', ' ') ?> €
                        </div>
                        <div>
                            <strong>Note:</strong> <?= number_format($recommended['rating_average'] ?? 0, 1) ?>/5
                        </div>
                        <div>
                            <strong>Expérience:</strong> <?= $recommended['total_reviews'] ?? 0 ?> avis
                        </div>
                    </div>
                    <p class="mt-2" style="opacity: 0.9;">
                        Ce choix offre le meilleur équilibre entre prix, qualité et expérience.
                    </p>
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <h4>Critères de sélection:</h4>
                <div class="grid grid-3">
                    <div>
                        <i class="fas fa-euro-sign" style="color: var(--secondary-color);"></i>
                        <strong>Prix compétitif</strong>
                        <p class="text-muted">Un tarif raisonnable par rapport au marché</p>
                    </div>
                    <div>
                        <i class="fas fa-star" style="color: var(--warning-color);"></i>
                        <strong>Bonne réputation</strong>
                        <p class="text-muted">Note moyenne élevée et avis positifs</p>
                    </div>
                    <div>
                        <i class="fas fa-briefcase" style="color: var(--primary-color);"></i>
                        <strong>Expérience</strong>
                        <p class="text-muted">Nombre de projets réalisés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = 'Comparaison des devis - ' . htmlspecialchars($project['title']); ?>
