<?php

namespace App\Controllers;

class Controller {
    protected function view($view, $data = []) {
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View not found: {$view}");
        }

        // Get the buffered content
        $content = ob_get_clean();

        // If layout exists, wrap content in it
        if (isset($layout)) {
            require __DIR__ . '/../Views/layouts/' . $layout . '.php';
        } else {
            echo $content;
        }
    }

    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($path) {
        header("Location: {$path}");
        exit;
    }

    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function session($key, $value = null) {
        if ($value === null) {
            return $_SESSION[$key] ?? null;
        }
        $_SESSION[$key] = $value;
    }

    protected function flash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }

    protected function getFlash($key) {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    protected function auth() {
        return $_SESSION['user'] ?? null;
    }

    protected function isAuth() {
        return isset($_SESSION['user']);
    }

    protected function isClient() {
        return $this->isAuth() && $_SESSION['user']['user_type'] === 'client';
    }

    protected function isArtisan() {
        return $this->isAuth() && $_SESSION['user']['user_type'] === 'artisan';
    }

    protected function isAdmin() {
        return $this->isAuth() && $_SESSION['user']['user_type'] === 'admin';
    }
}
