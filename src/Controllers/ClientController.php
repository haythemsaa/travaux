<?php

namespace App\Controllers;

use App\Models\Project;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Notification;
use App\Models\ArtisanProfile;
use App\Models\User;

class ClientController extends Controller {

    public function dashboard() {
        if (!$this->isClient()) {
            $this->redirect('/');
        }

        $projectModel = new Project();
        $projects = $projectModel->findByClientId($this->auth()['id']);

        $stats = [
            'total_projects' => count($projects),
            'active_projects' => count(array_filter($projects, fn($p) => $p['status'] === 'published')),
            'total_quotes' => array_sum(array_column($projects, 'quotes_count'))
        ];

        $this->view('client/dashboard', [
            'layout' => 'app',
            'projects' => $projects,
            'stats' => $stats
        ]);
    }

    public function createProjectForm() {
        if (!$this->isClient()) {
            $this->redirect('/');
        }

        $categoryModel = new Category();
        $categories = $categoryModel->findAll();

        $this->view('client/create-project', [
            'layout' => 'app',
            'categories' => $categories
        ]);
    }

    public function createProject() {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $errors = [];

        // Validation
        if (empty($_POST['title'])) {
            $errors[] = 'Le titre est requis';
        }

        if (empty($_POST['description'])) {
            $errors[] = 'La description est requise';
        }

        if (empty($_POST['category_id'])) {
            $errors[] = 'La catégorie est requise';
        }

        if (empty($_POST['city']) || empty($_POST['postal_code'])) {
            $errors[] = 'La ville et le code postal sont requis';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->redirect('/client/projects/create');
        }

        $projectModel = new Project();

        $projectId = $projectModel->create([
            'client_id' => $this->auth()['id'],
            'category_id' => $_POST['category_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'address' => $_POST['address'] ?? null,
            'city' => $_POST['city'],
            'postal_code' => $_POST['postal_code'],
            'budget_min' => $_POST['budget_min'] ?? null,
            'budget_max' => $_POST['budget_max'] ?? null,
            'start_date' => $_POST['start_date'] ?? null,
            'urgency' => $_POST['urgency'] ?? 'medium',
            'status' => 'published'
        ]);

        // Handle photo uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $uploadDir = __DIR__ . '/../../public/uploads/projects/';

            foreach ($_FILES['photos']['name'] as $key => $name) {
                if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($ext, $allowed)) {
                        $filename = uniqid('project_') . '.' . $ext;
                        $filepath = $uploadDir . $filename;

                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $filepath)) {
                            $projectModel->addPhoto($projectId, 'uploads/projects/' . $filename);
                        }
                    }
                }
            }
        }

        $this->flash('success', 'Votre projet a été publié avec succès!');
        $this->redirect('/client/projects/' . $projectId);
    }

    public function viewProject($id) {
        if (!$this->isClient()) {
            $this->redirect('/');
        }

        $projectModel = new Project();
        $project = $projectModel->findById($id);

        if (!$project || $project['client_id'] != $this->auth()['id']) {
            $this->redirect('/client/dashboard');
        }

        $quoteModel = new Quote();
        $quotes = $quoteModel->findByProjectId($id);
        $photos = $projectModel->getPhotos($id);

        $this->view('client/project-detail', [
            'layout' => 'app',
            'project' => $project,
            'quotes' => $quotes,
            'photos' => $photos
        ]);
    }

    public function acceptQuote($quoteId) {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $quoteModel = new Quote();
        $quote = $quoteModel->findById($quoteId);

        if (!$quote) {
            $this->json(['error' => 'Devis non trouvé'], 404);
        }

        // Verify ownership
        $projectModel = new Project();
        $project = $projectModel->findById($quote['project_id']);

        if ($project['client_id'] != $this->auth()['id']) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $quoteModel->updateStatus($quoteId, 'accepted');

        // Update project status
        $projectModel->update($quote['project_id'], ['status' => 'in_progress']);

        // Get artisan user_id and send notification
        $artisanModel = new ArtisanProfile();
        $artisan = $artisanModel->findById($quote['artisan_id']);
        if ($artisan) {
            Notification::notifyQuoteAccepted($artisan['user_id'], $project['title']);
        }

        $this->flash('success', 'Devis accepté avec succès!');
        $this->redirect('/client/projects/' . $quote['project_id']);
    }

    public function rejectQuote($quoteId) {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $quoteModel = new Quote();
        $quote = $quoteModel->findById($quoteId);

        if (!$quote) {
            $this->json(['error' => 'Devis non trouvé'], 404);
        }

        // Verify ownership
        $projectModel = new Project();
        $project = $projectModel->findById($quote['project_id']);

        if ($project['client_id'] != $this->auth()['id']) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $quoteModel->updateStatus($quoteId, 'rejected');

        $this->flash('success', 'Devis refusé');
        $this->redirect('/client/projects/' . $quote['project_id']);
    }
}
