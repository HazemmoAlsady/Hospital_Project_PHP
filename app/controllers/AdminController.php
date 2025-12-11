<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/MedicalRecord.php';

/**
 * AdminController - Pure MVC
 * Handles all admin-related actions
 */
class AdminController extends Controller {
    
    /**
     * Show admin dashboard  
     */
    public function dashboard() {
        $this->requireAuth('admin');
        
        // Get all data for admin view
        $users = User::all();
        $requests = Request::all();
        $records = MedicalRecord::all();
        
        // Calculate stats
        $totalUsers = count($users);
        $totalRequests = count($requests);
        $totalRecords = count($records);
        $adminCount = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
        $doctorCount = count(array_filter($users, fn($u) => $u['role'] === 'doctor'));
        $patientCount = count(array_filter($users, fn($u) => $u['role'] === 'patient'));
        
        // Pass data to view
        $this->view('admin/dashboard', [
            'users' => $users,
            'requests' => $requests,
            'records' => $records,
            'totalUsers' => $totalUsers,
            'totalRequests' => $totalRequests,
            'totalRecords' => $totalRecords,
            'adminCount' => $adminCount,
            'doctorCount' => $doctorCount,
            'patientCount' => $patientCount
        ]);
    }
    
    /**
     * Delete user
     */
    public function deleteUser() {
        $this->requireAuth('admin');
        
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            $this->redirect('/admin/dashboard');
        }
        
        User::delete($userId);
        
        $this->setFlash('success', 'تم حذف المستخدم بنجاح');
        $this->redirect('/admin/dashboard');
    }
}
