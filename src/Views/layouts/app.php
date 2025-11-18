<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Travaux Pro - Trouvez les meilleurs artisans' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="/" class="logo">
                    <i class="fas fa-hammer"></i> Travaux Pro
                </a>
                <ul class="nav-menu">
                    <li><a href="/projects" class="nav-link">Projets</a></li>
                    <li><a href="/how-it-works" class="nav-link">Comment ça marche</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['user_type'] === 'client'): ?>
                            <li><a href="/client/dashboard" class="nav-link">Tableau de bord</a></li>
                            <li><a href="/client/projects/create" class="btn btn-primary btn-sm">Publier un projet</a></li>
                        <?php elseif ($_SESSION['user']['user_type'] === 'artisan'): ?>
                            <li><a href="/artisan/dashboard" class="nav-link">Tableau de bord</a></li>
                            <li><a href="/artisan/projects" class="nav-link">Projets disponibles</a></li>
                        <?php endif; ?>
                        <li><a href="/logout" class="nav-link">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="/login" class="nav-link">Connexion</a></li>
                        <li><a href="/register" class="btn btn-primary btn-sm">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <?php if (isset($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="container mt-3">
                    <div class="alert alert-<?= $type === 'error' ? 'error' : 'success' ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Travaux Pro</h3>
                    <p>La plateforme n°1 pour mettre en relation particuliers et artisans professionnels.</p>
                </div>
                <div class="footer-section">
                    <h3>Pour les particuliers</h3>
                    <a href="/projects" class="footer-link">Trouver un artisan</a>
                    <a href="/how-it-works" class="footer-link">Comment ça marche</a>
                    <a href="/register" class="footer-link">Publier un projet</a>
                </div>
                <div class="footer-section">
                    <h3>Pour les artisans</h3>
                    <a href="/register" class="footer-link">Devenir partenaire</a>
                    <a href="/how-it-works" class="footer-link">Nos services</a>
                    <a href="/login" class="footer-link">Espace artisan</a>
                </div>
                <div class="footer-section">
                    <h3>À propos</h3>
                    <a href="/about" class="footer-link">Qui sommes-nous</a>
                    <a href="/contact" class="footer-link">Contact</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Travaux Pro. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="/js/app.js"></script>
</body>
</html>
