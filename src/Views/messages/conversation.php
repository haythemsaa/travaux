<div class="container mt-4">
    <a href="/messages" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour aux conversations
    </a>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><?= htmlspecialchars($project['title']) ?></h2>
            <p class="text-muted mb-0">Conversation avec l'autre partie</p>
        </div>
        <div class="card-body">
            <div style="max-height: 500px; overflow-y: auto; margin-bottom: 1rem;" id="messagesContainer">
                <?php if (empty($messages)): ?>
                    <p class="text-center text-muted">Aucun message pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="mb-3 <?= $message['sender_id'] == $_SESSION['user']['id'] ? 'text-right' : '' ?>">
                            <div class="d-inline-block"
                                 style="max-width: 70%; padding: 0.75rem; border-radius: var(--border-radius);
                                        background-color: <?= $message['sender_id'] == $_SESSION['user']['id'] ? 'var(--primary-color)' : 'var(--gray-200)' ?>;
                                        color: <?= $message['sender_id'] == $_SESSION['user']['id'] ? 'white' : 'var(--dark)' ?>;">
                                <strong class="d-block mb-1">
                                    <?= htmlspecialchars($message['sender_first_name'] . ' ' . $message['sender_last_name']) ?>
                                </strong>
                                <p class="mb-1"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                <small style="opacity: 0.8;">
                                    <?= date('d/m/Y H:i', strtotime($message['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <form action="/messages/send" method="POST">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                <input type="hidden" name="recipient_id" value="<?= $otherUserId ?>">

                <div class="form-group">
                    <textarea name="message" class="form-control" rows="3"
                              placeholder="Écrivez votre message..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom of messages
const messagesContainer = document.getElementById('messagesContainer');
if (messagesContainer) {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
</script>

<?php $title = 'Conversation - ' . htmlspecialchars($project['title']); ?>
