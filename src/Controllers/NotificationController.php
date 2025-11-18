<?php

namespace App\Controllers;

use App\Models\Notification;

class NotificationController extends Controller {

    public function index() {
        if (!$this->isAuth()) {
            $this->redirect('/login');
        }

        $notificationModel = new Notification();
        $notifications = $notificationModel->findByUserId($this->auth()['id']);
        $unreadCount = $notificationModel->getUnreadCount($this->auth()['id']);

        $this->view('notifications/index', [
            'layout' => 'app',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markAsRead($id) {
        if (!$this->isAuth()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $notificationModel = new Notification();
        $notificationModel->markAsRead($id);

        if (isset($_POST['redirect'])) {
            $this->redirect($_POST['redirect']);
        } else {
            $this->back();
        }
    }

    public function markAllAsRead() {
        if (!$this->isAuth()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $notificationModel = new Notification();
        $notificationModel->markAllAsRead($this->auth()['id']);

        $this->flash('success', 'Toutes les notifications ont été marquées comme lues');
        $this->redirect('/notifications');
    }

    public function delete($id) {
        if (!$this->isAuth()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $notificationModel = new Notification();
        $notificationModel->delete($id);

        $this->flash('success', 'Notification supprimée');
        $this->back();
    }

    public function getUnreadCount() {
        if (!$this->isAuth()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $notificationModel = new Notification();
        $count = $notificationModel->getUnreadCount($this->auth()['id']);

        $this->json(['count' => $count]);
    }
}
