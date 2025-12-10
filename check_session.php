<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "session_config.php";

echo "<h2>SESSION DEBUG</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
