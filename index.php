<?php

// Start session
session_start();

// Define paths
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Load config
require APP_PATH . '/config/database.php';
require APP_PATH . '/config/config.php';

// Route request
// Note: Router.php logic might need a small tweak to ignore 'assets' if not handled by .htaccess
(require APP_PATH . '/routes.php')->dispatch();
