<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\ArtisanProfile;

class AuthController extends Controller {

    public function showLogin() {
        $this->view('auth/login', ['layout' => 'app']);
    }

    public function showRegister() {
        $this->view('auth/register', ['layout' => 'app']);
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->flash('error', 'Veuillez remplir tous les champs');
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            $this->flash('error', 'Email ou mot de passe incorrect');
            $this->redirect('/login');
        }

        if (!$user['is_active']) {
            $this->flash('error', 'Votre compte est désactivé');
            $this->redirect('/login');
        }

        // Update last login
        $userModel->updateLastLogin($user['id']);

        // Set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name']
        ];

        // Get artisan profile if artisan
        if ($user['user_type'] === 'artisan') {
            $artisanModel = new ArtisanProfile();
            $profile = $artisanModel->findByUserId($user['id']);
            if ($profile) {
                $_SESSION['artisan_profile_id'] = $profile['id'];
            }
        }

        $this->flash('success', 'Connexion réussie!');

        // Redirect based on user type
        if ($user['user_type'] === 'client') {
            $this->redirect('/client/dashboard');
        } elseif ($user['user_type'] === 'artisan') {
            $this->redirect('/artisan/dashboard');
        } elseif ($user['user_type'] === 'admin') {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/');
        }
    }

    public function register() {
        $userType = $_POST['user_type'] ?? 'client';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Validation
        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Les mots de passe ne correspondent pas';
        }

        if (empty($firstName) || empty($lastName)) {
            $errors[] = 'Prénom et nom sont requis';
        }

        if (!in_array($userType, ['client', 'artisan'])) {
            $errors[] = 'Type d\'utilisateur invalide';
        }

        // Check if email already exists
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $errors[] = 'Cet email est déjà utilisé';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->redirect('/register');
        }

        // Create user
        $userId = $userModel->create([
            'email' => $email,
            'password' => $password,
            'user_type' => $userType,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone
        ]);

        // Create artisan profile if artisan
        if ($userType === 'artisan' && !empty($_POST['company_name'])) {
            $artisanModel = new ArtisanProfile();
            $artisanModel->create($userId, [
                'company_name' => $_POST['company_name'],
                'siret' => $_POST['siret'] ?? null,
                'city' => $_POST['city'] ?? null,
                'postal_code' => $_POST['postal_code'] ?? null
            ]);
        }

        $this->flash('success', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
        $this->redirect('/login');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
