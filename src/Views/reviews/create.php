<div class="container mt-4 mb-4">
    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Laisser un avis</h2>
            <p class="text-muted mb-0">
                Projet: <?= htmlspecialchars($project['title']) ?><br>
                Artisan: <?= htmlspecialchars($artisan['company_name']) ?>
            </p>
        </div>
        <div class="card-body">
            <form action="/reviews/create" method="POST">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                <input type="hidden" name="artisan_id" value="<?= $artisan['artisan_id'] ?>">

                <div class="form-group">
                    <label class="form-label">Note globale *</label>
                    <div class="rating-stars" data-rating="rating">
                        <i class="far fa-star" data-value="1"></i>
                        <i class="far fa-star" data-value="2"></i>
                        <i class="far fa-star" data-value="3"></i>
                        <i class="far fa-star" data-value="4"></i>
                        <i class="far fa-star" data-value="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="rating" required>
                </div>

                <div class="form-group">
                    <label for="title" class="form-label">Titre de l'avis</label>
                    <input type="text" id="title" name="title" class="form-control"
                           placeholder="Ex: Très satisfait des travaux">
                </div>

                <div class="form-group">
                    <label for="comment" class="form-label">Votre commentaire</label>
                    <textarea id="comment" name="comment" class="form-control" rows="5"
                              placeholder="Partagez votre expérience avec cet artisan..."></textarea>
                </div>

                <h4 class="mt-4 mb-3">Notes détaillées</h4>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Qualité des travaux</label>
                        <div class="rating-stars" data-rating="quality_rating">
                            <i class="far fa-star" data-value="1"></i>
                            <i class="far fa-star" data-value="2"></i>
                            <i class="far fa-star" data-value="3"></i>
                            <i class="far fa-star" data-value="4"></i>
                            <i class="far fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="quality_rating" id="quality_rating">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ponctualité</label>
                        <div class="rating-stars" data-rating="punctuality_rating">
                            <i class="far fa-star" data-value="1"></i>
                            <i class="far fa-star" data-value="2"></i>
                            <i class="far fa-star" data-value="3"></i>
                            <i class="far fa-star" data-value="4"></i>
                            <i class="far fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="punctuality_rating" id="punctuality_rating">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Communication</label>
                        <div class="rating-stars" data-rating="communication_rating">
                            <i class="far fa-star" data-value="1"></i>
                            <i class="far fa-star" data-value="2"></i>
                            <i class="far fa-star" data-value="3"></i>
                            <i class="far fa-star" data-value="4"></i>
                            <i class="far fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="communication_rating" id="communication_rating">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rapport qualité/prix</label>
                        <div class="rating-stars" data-rating="price_rating">
                            <i class="far fa-star" data-value="1"></i>
                            <i class="far fa-star" data-value="2"></i>
                            <i class="far fa-star" data-value="3"></i>
                            <i class="far fa-star" data-value="4"></i>
                            <i class="far fa-star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="price_rating" id="price_rating">
                    </div>
                </div>

                <div class="d-flex gap-2 justify-between mt-4">
                    <a href="/client/projects/<?= $project['id'] ?>" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">Publier l'avis</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.rating-stars {
    font-size: 1.5rem;
    cursor: pointer;
}

.rating-stars i {
    color: #ffc107;
    margin-right: 0.25rem;
}

.rating-stars i:hover,
.rating-stars i.active {
    font-weight: 900;
}
</style>

<script>
document.querySelectorAll('.rating-stars').forEach(container => {
    const inputName = container.getAttribute('data-rating');
    const input = document.getElementById(inputName);
    const stars = container.querySelectorAll('i');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            input.value = value;

            // Update visual
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                    s.classList.add('active');
                } else {
                    s.classList.remove('fas');
                    s.classList.remove('active');
                    s.classList.add('far');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const value = this.getAttribute('data-value');
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.add('fas');
                    s.classList.remove('far');
                } else {
                    s.classList.add('far');
                    s.classList.remove('fas');
                }
            });
        });
    });

    container.addEventListener('mouseleave', function() {
        const currentValue = input.value;
        stars.forEach((s, index) => {
            if (index < currentValue) {
                s.classList.add('fas');
                s.classList.remove('far');
            } else {
                s.classList.add('far');
                s.classList.remove('fas');
            }
        });
    });
});
</script>

<?php $title = 'Laisser un avis - Travaux Pro'; ?>
