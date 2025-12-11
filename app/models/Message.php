<?php
require_once __DIR__ . '/Model.php';

class Message extends Model {
    
    // Send message
    public static function send($senderId, $receiverId, $message) {
        return self::query(
            "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)",
            [$senderId, $receiverId, $message]
        );
    }
    
    // Get conversation between two users
    public static function getConversation($user1, $user2) {
        return self::fetchAll(
            "SELECT * FROM messages 
            WHERE (sender_id = ? AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = ?) 
            ORDER BY created_at ASC",
            [$user1, $user2, $user2, $user1]
        );
    }
    
    // Get recent messages for dashboard (with user names)
    public static function getRecentByUser($userId) {
        // This query fetches the last message exchanged with each user
        // and joins to get the names
        $sql = "
            SELECT m.*, 
                   s.name as sender_name, 
                   r.name as receiver_name
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            JOIN users r ON m.receiver_id = r.id
            WHERE m.id IN (
                SELECT MAX(id)
                FROM messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END
            )
            ORDER BY m.created_at DESC
        ";
        
        return self::fetchAll($sql, [$userId, $userId, $userId]);
    }
}
