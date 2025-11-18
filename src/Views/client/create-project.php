<div class="container mt-4 mb-4">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Publier un nouveau projet</h2>
            <p class="text-muted">Décrivez votre projet en détail pour recevoir les meilleurs devis</p>
        </div>
        <div class="card-body">
            <form action="/client/projects/create" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">Titre du projet *</label>
                    <input type="text" id="title" name="title" class="form-control"
                           placeholder="Ex: Rénovation complète d'une salle de bain" required>
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Catégorie *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description détaillée *</label>
                    <textarea id="description" name="description" class="form-control" rows="6"
                              placeholder="Décrivez votre projet en détail..." required></textarea>
                    <small class="text-muted">Plus votre description est détaillée, plus vous recevrez de devis pertinents.</small>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="city" class="form-label">Ville *</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="postal_code" class="form-label">Code postal *</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control"
                               pattern="[0-9]{5}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Adresse complète</label>
                    <input type="text" id="address" name="address" class="form-control">
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="budget_min" class="form-label">Budget minimum (€)</label>
                        <input type="number" id="budget_min" name="budget_min" class="form-control"
                               min="0" step="100">
                    </div>

                    <div class="form-group">
                        <label for="budget_max" class="form-label">Budget maximum (€)</label>
                        <input type="number" id="budget_max" name="budget_max" class="form-control"
                               min="0" step="100">
                    </div>
                </div>

                <div class="grid grid-2">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Date de début souhaitée</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="urgency" class="form-label">Urgence</label>
                        <select id="urgency" name="urgency" class="form-control">
                            <option value="low">Basse</option>
                            <option value="medium" selected>Moyenne</option>
                            <option value="high">Haute</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="photos" class="form-label">Photos du projet</label>
                    <input type="file" id="photos" name="photos[]" class="form-control"
                           accept="image/*" multiple>
                    <small class="text-muted">Vous pouvez ajouter jusqu'à 5 photos (formats: JPG, PNG, GIF)</small>
                </div>

                <div class="d-flex gap-2 justify-between">
                    <a href="/client/dashboard" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">Publier le projet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $title = 'Publier un projet - Travaux Pro'; ?>
