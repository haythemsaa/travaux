<?php

namespace App\Controllers;

use App\Models\Review;
use App\Models\Project;
use App\Models\Quote;
use App\Models\ArtisanProfile;

class ReviewController extends Controller {

    public function createForm($projectId) {
        if (!$this->isClient()) {
            $this->redirect('/');
        }

        $projectModel = new Project();
        $project = $projectModel->findById($projectId);

        if (!$project || $project['client_id'] != $this->auth()['id']) {
            $this->redirect('/client/dashboard');
        }

        // Get accepted quote to find artisan
        $quoteModel = new Quote();
        $quotes = $quoteModel->findByProjectId($projectId);
        $acceptedQuote = array_filter($quotes, fn($q) => $q['status'] === 'accepted');

        if (empty($acceptedQuote)) {
            $this->flash('error', 'Aucun devis accepté pour ce projet');
            $this->redirect('/client/projects/' . $projectId);
        }

        $acceptedQuote = reset($acceptedQuote);

        // Check if already reviewed
        $reviewModel = new Review();
        if ($reviewModel->checkExists($projectId, $this->auth()['id'])) {
            $this->flash('error', 'Vous avez déjà laissé un avis pour ce projet');
            $this->redirect('/client/projects/' . $projectId);
        }

        $this->view('reviews/create', [
            'layout' => 'app',
            'project' => $project,
            'artisan' => $acceptedQuote
        ]);
    }

    public function create() {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $errors = [];

        if (empty($_POST['project_id']) || empty($_POST['artisan_id'])) {
            $errors[] = 'Projet ou artisan invalide';
        }

        if (empty($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 5) {
            $errors[] = 'Note invalide';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
        }

        $reviewModel = new Review();

        $reviewModel->create([
            'project_id' => $_POST['project_id'],
            'artisan_id' => $_POST['artisan_id'],
            'client_id' => $this->auth()['id'],
            'rating' => $_POST['rating'],
            'title' => $_POST['title'] ?? null,
            'comment' => $_POST['comment'] ?? null,
            'quality_rating' => $_POST['quality_rating'] ?? $_POST['rating'],
            'punctuality_rating' => $_POST['punctuality_rating'] ?? $_POST['rating'],
            'communication_rating' => $_POST['communication_rating'] ?? $_POST['rating'],
            'price_rating' => $_POST['price_rating'] ?? $_POST['rating']
        ]);

        $this->flash('success', 'Merci pour votre avis!');
        $this->redirect('/client/projects/' . $_POST['project_id']);
    }

    public function artisanReviews($artisanId) {
        $reviewModel = new Review();
        $artisanModel = new ArtisanProfile();

        $artisan = $artisanModel->findById($artisanId);
        if (!$artisan) {
            $this->redirect('/');
        }

        $reviews = $reviewModel->findByArtisanId($artisanId);
        $stats = $reviewModel->getArtisanStats($artisanId);

        $this->view('reviews/artisan-reviews', [
            'layout' => 'app',
            'artisan' => $artisan,
            'reviews' => $reviews,
            'stats' => $stats
        ]);
    }

    public function respond($reviewId) {
        if (!$this->isArtisan()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $response = $_POST['response'] ?? '';

        if (empty($response)) {
            $this->flash('error', 'La réponse ne peut pas être vide');
            $this->back();
        }

        $reviewModel = new Review();
        $reviewModel->addArtisanResponse($reviewId, $response);

        $this->flash('success', 'Réponse ajoutée avec succès');
        $this->back();
    }
}
