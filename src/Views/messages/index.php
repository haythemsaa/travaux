<div class="container mt-4">
    <h1 class="mb-3">
        Messagerie
        <?php if ($unreadCount > 0): ?>
            <span class="badge badge-danger"><?= $unreadCount ?> nouveau<?= $unreadCount > 1 ? 'x' : '' ?></span>
        <?php endif; ?>
    </h1>

    <?php if (empty($conversations)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Vous n'avez pas encore de conversations.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($conversations as $conversation): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center mb-2">
                            <h3><?= htmlspecialchars($conversation['other_user_name']) ?></h3>
                            <?php if ($conversation['unread_count'] > 0): ?>
                                <span class="badge badge-danger"><?= $conversation['unread_count'] ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted mb-2">
                            <i class="fas fa-project-diagram"></i> <?= htmlspecialchars($conversation['project_title']) ?>
                        </p>
                        <p class="text-muted">
                            <small>Dernier message: <?= date('d/m/Y H:i', strtotime($conversation['last_message_date'])) ?></small>
                        </p>
                        <a href="/messages/conversation/<?= $conversation['project_id'] ?>/<?= $conversation['other_user_id'] ?>"
                           class="btn btn-primary btn-sm mt-2">
                            Voir la conversation
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $title = 'Messagerie - Travaux Pro'; ?>
