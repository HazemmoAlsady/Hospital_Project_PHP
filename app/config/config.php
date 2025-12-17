<?php
    // define URl 
$protocol = 'http';

$host = $_SERVER['HTTP_HOST'];

$script = dirname($_SERVER['SCRIPT_NAME']);

$script = rtrim(str_replace('\\', '/', $script), '/');

// Auto-detection can be flaky. Hardcoding for stability.
// define('BASE_URL', "$protocol://$host$script/");
define('BASE_URL', 'http://localhost/graduation_project/');
