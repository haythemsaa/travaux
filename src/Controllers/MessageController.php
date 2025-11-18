<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\Project;
use App\Models\Notification;

class MessageController extends Controller {

    public function index() {
        if (!$this->isAuth()) {
            $this->redirect('/login');
        }

        $messageModel = new Message();
        $conversations = $messageModel->getConversations($this->auth()['id']);
        $unreadCount = $messageModel->getUnreadCount($this->auth()['id']);

        $this->view('messages/index', [
            'layout' => 'app',
            'conversations' => $conversations,
            'unreadCount' => $unreadCount
        ]);
    }

    public function conversation($projectId, $otherUserId) {
        if (!$this->isAuth()) {
            $this->redirect('/login');
        }

        $messageModel = new Message();
        $projectModel = new Project();

        $project = $projectModel->findById($projectId);
        if (!$project) {
            $this->redirect('/messages');
        }

        $messages = $messageModel->getConversation($projectId, $this->auth()['id'], $otherUserId);

        // Mark messages as read
        $messageModel->markAsRead($projectId, $this->auth()['id']);

        $this->view('messages/conversation', [
            'layout' => 'app',
            'project' => $project,
            'messages' => $messages,
            'otherUserId' => $otherUserId
        ]);
    }

    public function send() {
        if (!$this->isAuth()) {
            $this->json(['error' => 'Non autorisé'], 403);
        }

        $projectId = $_POST['project_id'] ?? null;
        $recipientId = $_POST['recipient_id'] ?? null;
        $message = $_POST['message'] ?? '';

        if (empty($projectId) || empty($recipientId) || empty($message)) {
            $this->flash('error', 'Tous les champs sont requis');
            $this->back();
        }

        $messageModel = new Message();
        $result = $messageModel->create([
            'project_id' => $projectId,
            'sender_id' => $this->auth()['id'],
            'recipient_id' => $recipientId,
            'message' => $message
        ]);

        if ($result) {
            // Send notification
            $senderName = $this->auth()['first_name'] . ' ' . $this->auth()['last_name'];
            Notification::notifyNewMessage($recipientId, $senderName, $projectId);

            $this->flash('success', 'Message envoyé');
        } else {
            $this->flash('error', 'Erreur lors de l\'envoi du message');
        }

        $this->redirect("/messages/conversation/$projectId/$recipientId");
    }
}
