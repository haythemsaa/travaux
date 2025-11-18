<?php

namespace App\Controllers;

use App\Models\ArtisanProfile;
use App\Models\Category;

class SearchController extends Controller {

    public function artisans() {
        $artisanModel = new ArtisanProfile();
        $categoryModel = new Category();

        $filters = [
            'city' => $_GET['city'] ?? null,
            'specialty' => $_GET['specialty'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ];

        $artisans = $artisanModel->findAll(array_filter($filters));
        $categories = $categoryModel->findAll();

        $this->view('search/artisans', [
            'layout' => 'app',
            'artisans' => $artisans,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }

    public function artisanProfile($id) {
        $artisanModel = new ArtisanProfile();
        $artisan = $artisanModel->findById($id);

        if (!$artisan) {
            $this->redirect('/search/artisans');
        }

        // Get portfolio
        $portfolioModel = new \App\Models\Portfolio();
        $portfolio = $portfolioModel->findByArtisanId($id);

        // Get reviews
        $reviewModel = new \App\Models\Review();
        $reviews = $reviewModel->findByArtisanId($id);
        $stats = $reviewModel->getArtisanStats($id);

        // Get specialties
        $specialties = $artisanModel->getSpecialties($id);

        // Check if favorite (for logged in clients)
        $isFavorite = false;
        if ($this->isClient()) {
            $favoriteModel = new \App\Models\Favorite();
            $isFavorite = $favoriteModel->isFavorite($this->auth()['id'], $id);
        }

        $this->view('search/artisan-profile', [
            'layout' => 'app',
            'artisan' => $artisan,
            'portfolio' => $portfolio,
            'reviews' => $reviews,
            'stats' => $stats,
            'specialties' => $specialties,
            'isFavorite' => $isFavorite
        ]);
    }

    public function toggleFavorite($artisanId) {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $favoriteModel = new \App\Models\Favorite();
        $clientId = $this->auth()['id'];

        if ($favoriteModel->isFavorite($clientId, $artisanId)) {
            $favoriteModel->remove($clientId, $artisanId);
            $message = 'Retiré des favoris';
            $isFavorite = false;
        } else {
            $favoriteModel->add($clientId, $artisanId);
            $message = 'Ajouté aux favoris';
            $isFavorite = true;
        }

        if (isset($_POST['ajax'])) {
            $this->json(['success' => true, 'message' => $message, 'isFavorite' => $isFavorite]);
        } else {
            $this->flash('success', $message);
            $this->back();
        }
    }
}
