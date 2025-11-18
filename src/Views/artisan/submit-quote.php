<div class="container mt-4 mb-4">
    <a href="/artisan/projects/<?= $project['id'] ?>" class="btn btn-outline mb-3">
        <i class="fas fa-arrow-left"></i> Retour au projet
    </a>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Soumettre un devis</h2>
            <p class="text-muted"><?= htmlspecialchars($project['title']) ?></p>
        </div>
        <div class="card-body">
            <form action="/artisan/quotes/submit" method="POST">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">

                <div class="form-group">
                    <label for="amount" class="form-label">Montant du devis (€) *</label>
                    <input type="number" id="amount" name="amount" class="form-control"
                           min="0" step="0.01" required
                           placeholder="Ex: 2500.00">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description détaillée de votre devis *</label>
                    <textarea id="description" name="description" class="form-control" rows="6" required
                              placeholder="Décrivez en détail les travaux que vous proposez, les matériaux utilisés, etc."></textarea>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="estimated_duration" class="form-label">Durée estimée</label>
                        <input type="text" id="estimated_duration" name="estimated_duration" class="form-control"
                               placeholder="Ex: 2 semaines">
                    </div>

                    <div class="form-group">
                        <label for="start_date" class="form-label">Date de début possible</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_terms" class="form-label">Conditions de paiement</label>
                    <textarea id="payment_terms" name="payment_terms" class="form-control" rows="3"
                              placeholder="Ex: 30% à la commande, 40% à mi-parcours, 30% à la livraison"></textarea>
                </div>

                <div class="form-group">
                    <label for="valid_until" class="form-label">Validité du devis</label>
                    <input type="date" id="valid_until" name="valid_until" class="form-control">
                    <small class="text-muted">Date jusqu'à laquelle votre devis est valable</small>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>Important:</strong> Assurez-vous que votre devis soit détaillé et professionnel.
                    Un bon devis augmente vos chances d'être choisi par le client.
                </div>

                <div class="d-flex gap-2 justify-between">
                    <a href="/artisan/projects/<?= $project['id'] ?>" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">Envoyer le devis</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $title = 'Soumettre un devis - Travaux Pro'; ?>
