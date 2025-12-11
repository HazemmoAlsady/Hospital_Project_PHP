<?php
/**
 * Application Entry Point
 * Pure MVC Pattern
 * All requests go through this file
 */

// Start session with custom path to avoid permission issues
$sessionPath = __DIR__ . '/../tmp_sessions';
if (!file_exists($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
ini_set('session.save_path', $sessionPath);
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Load configuration
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/config/config.php';

log_debug("Request Incoming: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);

// Load router and routes
$router = require_once APP_PATH . '/routes.php';

// Dispatch the request
$router->dispatch();
