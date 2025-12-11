<?php
require_once __DIR__ . '/../config/database.php';

class Model {
    
    // Get PDO instance
    private static function getDB() {
        global $pdo;
        return $pdo;
    }

    protected static function query($sql, $params = []) {
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected static function fetchAll($sql, $params = []) {
        return self::query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function fetchOne($sql, $params = []) {
        return self::query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
}
