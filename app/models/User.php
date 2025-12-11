<?php
require_once __DIR__ . '/Model.php';

/**
 * User Model - handles all user-related database operations
 */
class User extends Model {
    
    /**
     * Find user by ID
     */
    public static function find($id) {
        return self::fetchOne(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }
    
    /**
     * Find user by email
     */
    public static function findByEmail($email) {
        return self::fetchOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }
    
    /**
     * Get all users
     */
    public static function all() {
        return self::fetchAll("SELECT * FROM users ORDER BY created_at DESC");
    }
    
    /**
     * Get users by role
     */
    public static function getByRole($role) {
        return self::fetchAll(
            "SELECT * FROM users WHERE role = ? ORDER BY created_at DESC",
            [$role]
        );
    }
    
    /**
     * Create new user
     */
    public static function create($data) {
        $sql = "INSERT INTO users (name, email, password, role, specialization, desired_doctor) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        return self::query($sql, [
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['specialization'] ?? null,
            $data['desired_doctor'] ?? null
        ]);
    }
    
    /**
     * Delete user
     */
    public static function delete($id) {
        return self::query("DELETE FROM users WHERE id = ?", [$id]);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($email, $password) {
        $user = self::findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
}
