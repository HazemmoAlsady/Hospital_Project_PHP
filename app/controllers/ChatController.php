<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/User.php';

/**
 * ChatController - Pure MVC
 * Handles messaging functionality
 */
class ChatController extends Controller {
    
    /**
     * Show chat page
     */
    public function show() {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $role = $this->getRole();
        
        // Get other user ID based on role
        if ($role === 'doctor') {
            $otherId = $_GET['patient_id'] ?? null;
        } else {
            $otherId = $_GET['doctor_id'] ?? null;
        }
        
        if (!$otherId) {
            $this->redirect('/' . $role . '/dashboard');
        }
        
        // Get messages
        $messages = Message::getConversation($userId, $otherId);
        $otherUser = User::find($otherId);
        
        $this->view('shared/chat', [
            'messages' => $messages,
            'otherUser' => $otherUser,
            'otherId' => $otherId,
            'loggedUser' => $userId,
            'role' => $role
        ]);
    }
    
    /**
     * Send message (POST)
     */
    public function sendMessage() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Invalid request'], 400);
        }
        
        $senderId = $this->getUserId();
        $receiverId = $_POST['receiver_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        
        if (!$receiverId || empty($message)) {
            $this->json(['error' => 'Missing data'], 400);
        }
        
        Message::send($senderId, $receiverId, $message);
        
        $this->json(['success' => true]);
    }
    
    /**
     * Fetch messages (AJAX)
     */
    public function fetchMessages() {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $otherId = $_GET['other_id'] ?? null;
        
        if (!$otherId) {
            $this->json(['error' => 'Missing other_id'], 400);
        }
        
        $messages = Message::getConversation($userId, $otherId);
        
        $this->json(['messages' => $messages]);
    }
}
