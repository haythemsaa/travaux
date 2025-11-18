<div class="container mt-4">
    <h1 class="mb-3">
        <i class="fas fa-chart-line"></i> Analytics & Performance
    </h1>

    <!-- Key Metrics -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <span class="stat-number"><?= $stats['projects_viewed'] ?? 0 ?></span>
            <span class="stat-label">Projets consultés (30j)</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['quotes_sent'] ?? 0 ?></span>
            <span class="stat-label">Devis envoyés</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['quotes_accepted'] ?? 0 ?></span>
            <span class="stat-label">Devis acceptés</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= number_format($conversionRate, 1) ?>%</span>
            <span class="stat-label">Taux de conversion</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['profile_views'] ?? 0 ?></span>
            <span class="stat-label">Vues du profil</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['messages_sent'] ?? 0 ?></span>
            <span class="stat-label">Messages envoyés</span>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="grid grid-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Performance Overview</h3>
            </div>
            <div class="card-body">
                <div class="performance-indicator">
                    <div class="d-flex justify-between mb-2">
                        <span>Taux de réponse</span>
                        <strong><?= number_format($conversionRate, 1) ?>%</strong>
                    </div>
                    <div class="progress mb-3" style="height: 12px; background-color: var(--gray-200); border-radius: 6px;">
                        <div style="width: <?= min($conversionRate, 100) ?>%; background: linear-gradient(90deg, var(--secondary-color), var(--primary-color)); border-radius: 6px;"></div>
                    </div>

                    <div class="d-flex justify-between mb-2">
                        <span>Efficacité</span>
                        <?php
                        $efficiency = 0;
                        if (($stats['projects_viewed'] ?? 0) > 0) {
                            $efficiency = (($stats['quotes_sent'] ?? 0) / $stats['projects_viewed']) * 100;
                        }
                        ?>
                        <strong><?= number_format($efficiency, 1) ?>%</strong>
                    </div>
                    <div class="progress mb-3" style="height: 12px; background-color: var(--gray-200); border-radius: 6px;">
                        <div style="width: <?= min($efficiency, 100) ?>%; background: linear-gradient(90deg, var(--warning-color), var(--secondary-color)); border-radius: 6px;"></div>
                    </div>

                    <div class="alert alert-<?= $conversionRate > 30 ? 'success' : ($conversionRate > 15 ? 'warning' : 'error') ?>">
                        <?php if ($conversionRate > 30): ?>
                            <i class="fas fa-trophy"></i> <strong>Excellent!</strong> Votre taux de conversion est supérieur à la moyenne.
                        <?php elseif ($conversionRate > 15): ?>
                            <i class="fas fa-thumbs-up"></i> <strong>Bien!</strong> Continuez à améliorer vos devis.
                        <?php else: ?>
                            <i class="fas fa-chart-line"></i> <strong>À améliorer.</strong> Essayez de personnaliser davantage vos devis.
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Activité récente</h3>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (empty($timeline)): ?>
                    <p class="text-center text-muted">Aucune activité récente</p>
                <?php else: ?>
                    <?php foreach ($timeline as $event): ?>
                        <?php
                        $icons = [
                            'project_view' => 'eye',
                            'quote_sent' => 'file-invoice',
                            'quote_accepted' => 'check-circle',
                            'message_sent' => 'envelope',
                            'profile_view' => 'user'
                        ];
                        $colors = [
                            'project_view' => 'primary',
                            'quote_sent' => 'warning',
                            'quote_accepted' => 'success',
                            'message_sent' => 'secondary',
                            'profile_view' => 'primary'
                        ];
                        $icon = $icons[$event['event_type']] ?? 'circle';
                        $color = $colors[$event['event_type']] ?? 'gray';
                        ?>
                        <div class="d-flex gap-2 mb-3">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--<?= $color ?>-color); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-<?= $icon ?>"></i>
                            </div>
                            <div style="flex: 1;">
                                <strong style="text-transform: capitalize;">
                                    <?= str_replace('_', ' ', $event['event_type']) ?>
                                </strong>
                                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                    <?= date('d/m/Y H:i', strtotime($event['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-lightbulb"></i> Recommandations pour améliorer vos performances
            </h3>
        </div>
        <div class="card-body">
            <div class="grid grid-3">
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-bolt" style="color: var(--warning-color);"></i> Réactivité
                    </h4>
                    <p class="text-muted">Répondez aux projets dans les 2 heures pour augmenter vos chances de 60%.</p>
                </div>
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-star" style="color: var(--warning-color);"></i> Qualité
                    </h4>
                    <p class="text-muted">Maintenez une note moyenne de 4.5/5 pour apparaître en haut des résultats.</p>
                </div>
                <div>
                    <h4 style="font-size: 1rem;">
                        <i class="fas fa-images" style="color: var(--primary-color);"></i> Portfolio
                    </h4>
                    <p class="text-muted">Ajoutez des photos de vos réalisations pour augmenter la confiance.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $title = 'Analytics & Performance - Travaux Pro'; ?>
