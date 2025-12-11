<?php
/**
 * Router for PHP Built-in Server
 * Use: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly (CSS, JS, images)
if (preg_match('/\.(?:css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/i', $uri)) {
    // 1. Check relative to project root (e.g. /public/assets/style.css)
    if (file_exists(__DIR__ . $uri)) {
        return false; 
    }
    // 2. Check inside public folder (e.g. /assets/style.css)
    if (file_exists(__DIR__ . '/public' . $uri)) {
        return false;
    }
}

// All other requests go to public/index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/public/index.php';
