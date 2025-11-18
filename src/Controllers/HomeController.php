<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Project;

class HomeController extends Controller {

    public function index() {
        $categoryModel = new Category();
        $projectModel = new Project();

        $categories = $categoryModel->findAll();
        $recentProjects = $projectModel->findAll(['limit' => 6]);

        $this->view('home/index', [
            'layout' => 'app',
            'categories' => $categories,
            'projects' => $recentProjects
        ]);
    }

    public function projects() {
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

        $this->view('home/projects', [
            'layout' => 'app',
            'projects' => $projects,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }

    public function projectDetail($id) {
        $projectModel = new Project();
        $project = $projectModel->findById($id);

        if (!$project) {
            http_response_code(404);
            $this->view('errors/404', ['layout' => 'app']);
            return;
        }

        // Increment views
        $projectModel->incrementViews($id);

        // Get photos
        $photos = $projectModel->getPhotos($id);

        $this->view('home/project-detail', [
            'layout' => 'app',
            'project' => $project,
            'photos' => $photos
        ]);
    }

    public function howItWorks() {
        $this->view('home/how-it-works', ['layout' => 'app']);
    }

    public function about() {
        $this->view('home/about', ['layout' => 'app']);
    }

    public function contact() {
        $this->view('home/contact', ['layout' => 'app']);
    }
}
