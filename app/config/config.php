<?php
/**
 * Configuration File
 */

// Detect Base URL automatically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);

// Normalize slashes
$script = str_replace('\\', '/', $script);

// Remove public from script path if it exists to get root
$script = str_replace('/public', '', $script);

// Remove trailing slash if exists
$script = rtrim($script, '/');
define('BASE_URL', $protocol . '://' . $host . $script . '/');

// Debug Logger Function
function log_debug($message) {
    global $script; // Use script path or __DIR__
    $logFile = dirname(__DIR__, 2) . '/debug_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $content = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $content, FILE_APPEND);
}

log_debug("Config loaded. BASE_URL: " . BASE_URL);
