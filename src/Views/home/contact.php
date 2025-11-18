<section class="hero">
    <div class="container">
        <h1>Contactez-nous</h1>
        <p>Notre équipe est à votre écoute</p>
    </div>
</section>

<section class="container mt-4 mb-4">
    <div class="grid grid-2">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Envoyez-nous un message</h2>
            </div>
            <div class="card-body">
                <form action="/contact/send" method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">Sujet *</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">Sélectionnez un sujet</option>
                            <option value="question">Question générale</option>
                            <option value="support">Support technique</option>
                            <option value="partnership">Devenir partenaire</option>
                            <option value="complaint">Réclamation</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </form>
            </div>
        </div>

        <div>
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Coordonnées</h3>
                </div>
                <div class="card-body">
                    <p>
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>Adresse</strong><br>
                        123 Avenue des Artisans<br>
                        75001 Paris, France
                    </p>

                    <p class="mt-3">
                        <i class="fas fa-phone"></i>
                        <strong>Téléphone</strong><br>
                        <a href="tel:+33123456789">01 23 45 67 89</a>
                    </p>

                    <p class="mt-3">
                        <i class="fas fa-envelope"></i>
                        <strong>Email</strong><br>
                        <a href="mailto:contact@travaux-pro.fr">contact@travaux-pro.fr</a>
                    </p>

                    <p class="mt-3">
                        <i class="fas fa-clock"></i>
                        <strong>Horaires</strong><br>
                        Lundi - Vendredi: 9h00 - 18h00<br>
                        Samedi: 9h00 - 12h00<br>
                        Dimanche: Fermé
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">FAQ</h3>
                </div>
                <div class="card-body">
                    <h4 style="font-size: 1rem;">Est-ce gratuit ?</h4>
                    <p class="text-muted mb-3">
                        Oui, la publication de projets et la création de compte sont entièrement gratuites pour les particuliers.
                    </p>

                    <h4 style="font-size: 1rem;">Comment choisir un artisan ?</h4>
                    <p class="text-muted mb-3">
                        Consultez les profils, les avis clients, comparez les devis et choisissez l'artisan qui correspond le mieux à vos besoins.
                    </p>

                    <h4 style="font-size: 1rem;">Les artisans sont-ils vérifiés ?</h4>
                    <p class="text-muted mb-3">
                        Oui, nous vérifions l'identité et les qualifications de tous nos artisans partenaires.
                    </p>

                    <a href="/how-it-works" class="btn btn-outline btn-sm">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $title = 'Contact - Travaux Pro'; ?>
