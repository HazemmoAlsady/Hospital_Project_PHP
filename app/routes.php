<?php
/**
 * Routes Definition
 * Define all application routes here
 */

require_once __DIR__ . '/Router.php';

$router = new Router();

// ========================================
// Authentication Routes
// ========================================
$router->get('/', 'AuthController', 'showLogin');
$router->get('/login', 'AuthController', 'showLogin'); // Alias for /
$router->get('/debug-db', 'AuthController', 'debugRequests'); // DEBUG ROUTE
$router->post('/', 'AuthController', 'login'); // Fallback for empty action
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/dashboard', 'AuthController', 'showLogin'); // Fallback

// ========================================
// Patient Routes
// ========================================
$router->get('/patient/dashboard', 'PatientController', 'dashboard');
$router->post('/patient/request/create', 'PatientController', 'createRequest');
// Redirect GET access to create request back to dashboard
$router->get('/patient/request/create', 'PatientController', 'dashboard');

// ========================================
// Doctor Routes
// ========================================
$router->get('/doctor/dashboard', 'DoctorController', 'dashboard');
$router->get('/doctor/request/reply', 'DoctorController', 'replyToRequest');
$router->get('/doctor/request/update', 'DoctorController', 'updateRequest');
$router->post('/doctor/reply/create', 'DoctorController', 'createReply');
$router->get('/doctor/record/create', 'DoctorController', 'showCreateRecord');
$router->post('/doctor/record/create', 'DoctorController', 'createMedicalRecord');
$router->get('/doctor/records/show', 'DoctorController', 'showMedicalRecords');

// ========================================
// Admin Routes
// ========================================
$router->get('/admin/dashboard', 'AdminController', 'dashboard');
$router->get('/admin/user/delete', 'AdminController', 'deleteUser');

// ========================================
// Chat Routes
// ========================================
$router->get('/chat', 'ChatController', 'show');
$router->post('/chat/send', 'ChatController', 'sendMessage');
$router->get('/chat/fetch', 'ChatController', 'fetchMessages');

return $router;
