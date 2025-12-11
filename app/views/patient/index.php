<?php
/**
 * Patient Dashboard Entry Point
 * This file now acts as a front controller
 */

// Start session
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/patient/PatientController.php';

// Create controller instance
$controller = new PatientController();

// Call dashboard method
$controller->dashboard();
