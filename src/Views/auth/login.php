<section class="container mt-4 mb-4">
    <div class="card" style="max-width: 500px; margin: 2rem auto;">
        <div class="card-header">
            <h2 class="card-title text-center">Connexion</h2>
            <p class="text-center text-muted">Connectez-vous à votre espace</p>
        </div>
        <div class="card-body">
            <form action="/login" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-full">Se connecter</button>
            </form>

            <p class="text-center mt-3">
                Pas encore de compte ? <a href="/register">Inscrivez-vous</a>
            </p>
        </div>
    </div>
</section>
<?php $title = 'Connexion - Travaux Pro'; ?>
