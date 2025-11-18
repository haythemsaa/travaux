<?php

namespace App\Controllers;

use App\Models\Quote;
use App\Models\Project;
use App\Models\Review;
use App\Utils\PDFGenerator;

class QuoteComparisonController extends Controller {

    public function compare($projectId) {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $projectModel = new Project();
        $project = $projectModel->findById($projectId);

        if (!$project || $project['client_id'] != $this->auth()['id']) {
            $this->redirect('/client/dashboard');
        }

        $quoteModel = new Quote();
        $quotes = $quoteModel->findByProjectId($projectId);

        if (empty($quotes)) {
            $this->flash('error', 'Aucun devis à comparer pour ce projet');
            $this->redirect('/client/projects/' . $projectId);
        }

        // Calculate comparison metrics
        $comparison = $this->calculateComparison($quotes);

        $this->view('quotes/comparison', [
            'layout' => 'app',
            'project' => $project,
            'quotes' => $quotes,
            'comparison' => $comparison
        ]);
    }

    private function calculateComparison($quotes) {
        $amounts = array_column($quotes, 'amount');
        $ratings = array_column($quotes, 'rating_average');

        return [
            'lowest_price' => !empty($amounts) ? min($amounts) : 0,
            'highest_price' => !empty($amounts) ? max($amounts) : 0,
            'average_price' => !empty($amounts) ? array_sum($amounts) / count($amounts) : 0,
            'best_rated' => !empty($ratings) ? max($ratings) : 0,
            'total_quotes' => count($quotes)
        ];
    }

    public function exportPDF($projectId) {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $projectModel = new Project();
        $project = $projectModel->findById($projectId);

        if (!$project || $project['client_id'] != $this->auth()['id']) {
            http_response_code(403);
            echo "Non autorisé";
            exit;
        }

        $quoteModel = new Quote();
        $quotes = $quoteModel->findByProjectId($projectId);

        $pdf = PDFGenerator::generateComparisonPDF($quotes, $project);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="comparaison_devis_' . $projectId . '.html"');
        echo $pdf;
        exit;
    }

    public function exportQuotePDF($quoteId) {
        $quoteModel = new Quote();
        $quote = $quoteModel->findById($quoteId);

        if (!$quote) {
            http_response_code(404);
            echo "Devis non trouvé";
            exit;
        }

        // Check permissions
        if ($this->isClient()) {
            $projectModel = new Project();
            $project = $projectModel->findById($quote['project_id']);
            if ($project['client_id'] != $this->auth()['id']) {
                http_response_code(403);
                echo "Non autorisé";
                exit;
            }
        } elseif ($this->isArtisan()) {
            // Check if artisan owns this quote
            $artisanId = $_SESSION['artisan_profile_id'] ?? null;
            if ($quote['artisan_id'] != $artisanId) {
                http_response_code(403);
                echo "Non autorisé";
                exit;
            }
        } else {
            http_response_code(403);
            echo "Non autorisé";
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findById($quote['project_id']);

        $artisanModel = new \App\Models\ArtisanProfile();
        $artisan = $artisanModel->findById($quote['artisan_id']);

        $pdf = PDFGenerator::generateQuotePDF($quote, $project, $artisan);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="devis_' . $quoteId . '.html"');
        echo $pdf;
        exit;
    }

    public function smartRecommendations($projectId) {
        if (!$this->isClient()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $projectModel = new Project();
        $project = $projectModel->findById($projectId);

        if (!$project || $project['client_id'] != $this->auth()['id']) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $quoteModel = new Quote();
        $quotes = $quoteModel->findByProjectId($projectId);

        // Smart ranking algorithm
        $recommendations = [];
        foreach ($quotes as $quote) {
            $score = $this->calculateSmartScore($quote, $project);
            $recommendations[] = array_merge($quote, ['smart_score' => $score]);
        }

        // Sort by score
        usort($recommendations, function($a, $b) {
            return $b['smart_score'] <=> $a['smart_score'];
        });

        $this->json([
            'recommendations' => $recommendations,
            'best_choice' => $recommendations[0] ?? null
        ]);
    }

    private function calculateSmartScore($quote, $project) {
        $score = 50; // Base score

        // Price factor (prefer mid-range)
        if ($project['budget_max'] && $quote['amount'] <= $project['budget_max']) {
            $score += 20;
        }

        // Rating factor
        $score += ($quote['rating_average'] ?? 0) * 4; // Max 20 points

        // Review count factor
        $reviewBonus = min($quote['total_reviews'] ?? 0, 10) * 1; // Max 10 points
        $score += $reviewBonus;

        // Response time bonus (if quote was quick)
        // This would require tracking quote response time

        return round($score, 2);
    }
}
