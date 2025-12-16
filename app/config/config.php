<?php
    // define URl 
$protocol = 'http';

$host = $_SERVER['HTTP_HOST'];

$script = dirname($_SERVER['SCRIPT_NAME']);

$script = rtrim(str_replace('\\', '/', $script), '/');

define('BASE_URL', "$protocol://$host$script/");
