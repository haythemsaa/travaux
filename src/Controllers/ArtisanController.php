<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Quote;
use App\Models\ArtisanProfile;
use App\Models\Category;

class ArtisanController extends Controller {

    public function dashboard() {
        if (!$this->isArtisan()) {
            $this->redirect('/');
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;

        if (!$artisanId) {
            $this->flash('error', 'Profil artisan non trouvé');
            $this->redirect('/');
        }

        $quoteModel = new Quote();
        $projectModel = new Project();

        $quotes = $quoteModel->findByArtisanId($artisanId);
        $recentProjects = $projectModel->findAll(['limit' => 10]);

        $stats = [
            'total_quotes' => count($quotes),
            'pending_quotes' => count(array_filter($quotes, fn($q) => $q['status'] === 'pending')),
            'accepted_quotes' => count(array_filter($quotes, fn($q) => $q['status'] === 'accepted'))
        ];

        $this->view('artisan/dashboard', [
            'layout' => 'app',
            'quotes' => $quotes,
            'projects' => $recentProjects,
            'stats' => $stats
        ]);
    }

    public function projects() {
        if (!$this->isArtisan()) {
            $this->redirect('/');
        }

        $projectModel = new Project();
        $categoryModel = new Category();

        $filters = [
            'category_id' => $_GET['category'] ?? null,
            'city' => $_GET['city'] ?? null,
            'postal_code' => $_GET['postal_code'] ?? null,
            'urgency' => $_GET['urgency'] ?? null
        ];

        $projects = $projectModel->findAll(array_filter($filters));
        $categories = $categoryModel->findAll();

        $this->view('artisan/projects', [
            'layout' => 'app',
            'projects' => $projects,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }

    public function viewProject($id) {
        if (!$this->isArtisan()) {
            $this->redirect('/');
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;

        $projectModel = new Project();
        $artisanModel = new ArtisanProfile();

        $project = $projectModel->findById($id);

        if (!$project) {
            $this->redirect('/artisan/projects');
        }

        // Check if artisan has access to contact details
        $hasAccess = $artisanModel->hasAccessToProject($artisanId, $id);

        // Get photos
        $photos = $projectModel->getPhotos($id);

        // Check if already quoted
        $quoteModel = new Quote();
        $hasQuoted = $quoteModel->checkExists($id, $artisanId);

        $this->view('artisan/project-detail', [
            'layout' => 'app',
            'project' => $project,
            'photos' => $photos,
            'hasAccess' => $hasAccess,
            'hasQuoted' => $hasQuoted
        ]);
    }

    public function unlockProject($id) {
        if (!$this->isArtisan()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;
        $artisanModel = new ArtisanProfile();

        // In a real app, this would handle payment
        // For now, we'll just grant access for free
        $artisanModel->grantProjectAccess($artisanId, $id, 0);

        $this->flash('success', 'Coordonnées du client débloquées!');
        $this->redirect('/artisan/projects/' . $id);
    }

    public function submitQuoteForm($projectId) {
        if (!$this->isArtisan()) {
            $this->redirect('/');
        }

        $projectModel = new Project();
        $project = $projectModel->findById($projectId);

        if (!$project) {
            $this->redirect('/artisan/projects');
        }

        $this->view('artisan/submit-quote', [
            'layout' => 'app',
            'project' => $project
        ]);
    }

    public function submitQuote() {
        if (!$this->isArtisan()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;
        $projectId = $_POST['project_id'] ?? null;

        $errors = [];

        if (empty($projectId)) {
            $errors[] = 'Projet invalide';
        }

        if (empty($_POST['amount']) || $_POST['amount'] <= 0) {
            $errors[] = 'Le montant doit être supérieur à 0';
        }

        if (empty($_POST['description'])) {
            $errors[] = 'La description est requise';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
        }

        $quoteModel = new Quote();

        // Check if already submitted
        if ($quoteModel->checkExists($projectId, $artisanId)) {
            $this->flash('error', 'Vous avez déjà soumis un devis pour ce projet');
            $this->redirect('/artisan/projects/' . $projectId);
        }

        $quoteModel->create([
            'project_id' => $projectId,
            'artisan_id' => $artisanId,
            'amount' => $_POST['amount'],
            'description' => $_POST['description'],
            'estimated_duration' => $_POST['estimated_duration'] ?? null,
            'start_date' => $_POST['start_date'] ?? null,
            'payment_terms' => $_POST['payment_terms'] ?? null,
            'valid_until' => $_POST['valid_until'] ?? null
        ]);

        $this->flash('success', 'Votre devis a été envoyé avec succès!');
        $this->redirect('/artisan/dashboard');
    }

    public function profile() {
        if (!$this->isArtisan()) {
            $this->redirect('/');
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;
        $artisanModel = new ArtisanProfile();

        $profile = $artisanModel->findById($artisanId);

        $this->view('artisan/profile', [
            'layout' => 'app',
            'profile' => $profile
        ]);
    }

    public function updateProfile() {
        if (!$this->isArtisan()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $artisanId = $_SESSION['artisan_profile_id'] ?? null;
        $artisanModel = new ArtisanProfile();

        $artisanModel->update($artisanId, [
            'company_name' => $_POST['company_name'] ?? null,
            'description' => $_POST['description'] ?? null,
            'address' => $_POST['address'] ?? null,
            'city' => $_POST['city'] ?? null,
            'postal_code' => $_POST['postal_code'] ?? null,
            'website' => $_POST['website'] ?? null,
            'years_experience' => $_POST['years_experience'] ?? null
        ]);

        $this->flash('success', 'Profil mis à jour avec succès!');
        $this->redirect('/artisan/profile');
    }
}
