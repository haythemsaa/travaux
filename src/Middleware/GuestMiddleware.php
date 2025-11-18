<?php

namespace App\Middleware;

class GuestMiddleware {
    public function handle() {
        if (isset($_SESSION['user'])) {
            $userType = $_SESSION['user']['user_type'];
            if ($userType === 'client') {
                header('Location: /client/dashboard');
            } elseif ($userType === 'artisan') {
                header('Location: /artisan/dashboard');
            } elseif ($userType === 'admin') {
                header('Location: /admin/dashboard');
            }
            exit;
        }
        return true;
    }
}
