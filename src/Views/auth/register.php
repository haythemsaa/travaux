<section class="container mt-4 mb-4">
    <div class="card" style="max-width: 600px; margin: 2rem auto;">
        <div class="card-header">
            <h2 class="card-title text-center">Inscription</h2>
            <p class="text-center text-muted">Créez votre compte gratuitement</p>
        </div>
        <div class="card-body">
            <form action="/register" method="POST" id="registerForm">
                <div class="form-group">
                    <label class="form-label">Je suis :</label>
                    <div class="d-flex gap-2">
                        <label style="flex: 1; cursor: pointer;">
                            <input type="radio" name="user_type" value="client" checked onchange="toggleArtisanFields()">
                            <span class="btn btn-outline w-full" id="clientBtn">Particulier</span>
                        </label>
                        <label style="flex: 1; cursor: pointer;">
                            <input type="radio" name="user_type" value="artisan" onchange="toggleArtisanFields()">
                            <span class="btn btn-outline w-full" id="artisanBtn">Artisan</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="first_name" class="form-label">Prénom</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name" class="form-label">Nom</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required>
                </div>

                <div id="artisanFields" style="display: none;">
                    <div class="form-group">
                        <label for="company_name" class="form-label">Nom de l'entreprise</label>
                        <input type="text" id="company_name" name="company_name" class="form-control">
                    </div>

                    <div class="grid grid-2">
                        <div class="form-group">
                            <label for="siret" class="form-label">SIRET</label>
                            <input type="text" id="siret" name="siret" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" id="city" name="city" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full">S'inscrire</button>
            </form>

            <p class="text-center mt-3">
                Déjà un compte ? <a href="/login">Connectez-vous</a>
            </p>
        </div>
    </div>
</section>

<script>
function toggleArtisanFields() {
    const artisanRadio = document.querySelector('input[name="user_type"][value="artisan"]');
    const artisanFields = document.getElementById('artisanFields');
    const companyName = document.getElementById('company_name');

    if (artisanRadio.checked) {
        artisanFields.style.display = 'block';
        companyName.required = true;
    } else {
        artisanFields.style.display = 'none';
        companyName.required = false;
    }
}
</script>

<?php $title = 'Inscription - Travaux Pro'; ?>
