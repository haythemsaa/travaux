<div class="container mt-4">
    <div class="d-flex justify-between align-center mb-3">
        <h1>Mon Portfolio</h1>
        <a href="/artisan/portfolio/add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une réalisation
        </a>
    </div>

    <?php if (empty($portfolio)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Vous n'avez pas encore ajouté de réalisations à votre portfolio.</p>
                <a href="/artisan/portfolio/add" class="btn btn-primary mt-2">
                    Ajouter votre première réalisation
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($portfolio as $item): ?>
                <div class="card">
                    <img src="/<?= htmlspecialchars($item['photo_path']) ?>"
                         alt="<?= htmlspecialchars($item['title']) ?>"
                         style="width: 100%; height: 200px; object-fit: cover; border-radius: var(--border-radius) var(--border-radius) 0 0;">
                    <div class="card-body">
                        <h3 style="font-size: 1.25rem;"><?= htmlspecialchars($item['title']) ?></h3>
                        <?php if ($item['category_name']): ?>
                            <span class="badge badge-primary"><?= htmlspecialchars($item['category_name']) ?></span>
                        <?php endif; ?>

                        <?php if ($item['description']): ?>
                            <p class="text-muted mt-2">
                                <?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...
                            </p>
                        <?php endif; ?>

                        <?php if ($item['completion_date']): ?>
                            <p class="text-muted">
                                <i class="fas fa-calendar"></i> <?= date('m/Y', strtotime($item['completion_date'])) ?>
                            </p>
                        <?php endif; ?>

                        <div class="d-flex gap-1 mt-2">
                            <a href="/artisan/portfolio/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="/artisan/portfolio/delete/<?= $item['id'] ?>" method="POST"
                                  style="display: inline;"
                                  onsubmit="return confirm('Supprimer cette réalisation ?')">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $title = 'Mon Portfolio - Travaux Pro'; ?>
