<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-3">
        <h1>Notifications</h1>
        <?php if ($unreadCount > 0): ?>
            <form action="/notifications/mark-all-read" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-outline btn-sm">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
            </form>
        <?php endif; ?>
    </div>

    <?php if (empty($notifications)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Vous n'avez pas de notifications.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <?php foreach ($notifications as $notification): ?>
                    <div class="card mb-2 <?= !$notification['is_read'] ? 'border-primary' : '' ?>"
                         style="<?= !$notification['is_read'] ? 'border-left: 4px solid var(--primary-color);' : '' ?>">
                        <div class="card-body">
                            <div class="d-flex justify-between align-center">
                                <div style="flex: 1;">
                                    <h4 style="font-size: 1rem; margin-bottom: 0.5rem;">
                                        <?php
                                        $icons = [
                                            'new_quote' => 'file-invoice',
                                            'quote_accepted' => 'check-circle',
                                            'new_message' => 'envelope',
                                            'new_review' => 'star'
                                        ];
                                        $icon = $icons[$notification['type']] ?? 'bell';
                                        ?>
                                        <i class="fas fa-<?= $icon ?>"></i>
                                        <?= htmlspecialchars($notification['title']) ?>
                                        <?php if (!$notification['is_read']): ?>
                                            <span class="badge badge-primary">Nouveau</span>
                                        <?php endif; ?>
                                    </h4>
                                    <p class="text-muted mb-2"><?= htmlspecialchars($notification['message']) ?></p>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="d-flex gap-1" style="margin-left: 1rem;">
                                    <?php if ($notification['link']): ?>
                                        <a href="<?= htmlspecialchars($notification['link']) ?>"
                                           class="btn btn-sm btn-primary">
                                            Voir
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!$notification['is_read']): ?>
                                        <form action="/notifications/mark-read/<?= $notification['id'] ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-sm btn-outline">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="/notifications/delete/<?= $notification['id'] ?>" method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Supprimer cette notification ?')">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $title = 'Notifications - Travaux Pro'; ?>
