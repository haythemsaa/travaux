<?php

namespace App\Controllers;

use App\Models\Analytics;
use App\Models\PriceStatistics;

class AnalyticsController extends Controller {

    public function dashboard() {
        if (!$this->isAuth()) {
            $this->redirect('/login');
        }

        $analyticsModel = new Analytics();
        $userId = $this->auth()['id'];
        $userType = $this->auth()['user_type'];

        if ($userType === 'artisan') {
            $stats = $analyticsModel->getArtisanStats($userId, 30);
            $conversionRate = $analyticsModel->getConversionRate($userId, 30);
            $timeline = $analyticsModel->getActivityTimeline($userId, 20);

            $this->view('analytics/artisan-dashboard', [
                'layout' => 'app',
                'stats' => $stats,
                'conversionRate' => $conversionRate,
                'timeline' => $timeline
            ]);
        } elseif ($userType === 'client') {
            $stats = $analyticsModel->getClientStats($userId, 30);
            $timeline = $analyticsModel->getActivityTimeline($userId, 20);

            $this->view('analytics/client-dashboard', [
                'layout' => 'app',
                'stats' => $stats,
                'timeline' => $timeline
            ]);
        }
    }

    public function marketPrices() {
        $categoryId = $_GET['category'] ?? null;
        $city = $_GET['city'] ?? null;

        $priceModel = new PriceStatistics();

        if ($categoryId && $city) {
            $estimate = $priceModel->getEstimatedPrice($categoryId, $city);
            $this->json($estimate);
        } else {
            $analyticsModel = new Analytics();
            $popularCategories = $analyticsModel->getPopularCategories();

            $this->view('analytics/market-prices', [
                'layout' => 'app',
                'categories' => $popularCategories
            ]);
        }
    }

    public function compareProject($projectId) {
        $priceModel = new PriceStatistics();
        $comparison = $priceModel->compareWithMarket($projectId);

        if (!$comparison) {
            $this->flash('error', 'Projet non trouvé');
            $this->redirect('/');
        }

        $this->view('analytics/project-comparison', [
            'layout' => 'app',
            'comparison' => $comparison
        ]);
    }
}
