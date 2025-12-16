<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/MedicalRecord.php';

/**
 * PatientController - Pure MVC
 * Handles all patient-related actions
 */
class PatientController extends Controller {
    
    /**
     * Show patient dashboard
     */
    public function dashboard() {
        // Require patient authentication
        $this->requireAuth('patient');
        
        // Handle POST requests (Create Request Fallback)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createRequest();
            return;
        }
        
        $patientId = $this->getUserId();
        
        // Get data from Models
        $patient = User::find($patientId);
        $requests = Request::getByPatient($patientId);
        $records = MedicalRecord::getByPatient($patientId);
        
        // Pass data to view
        $this->view('patient/dashboard', [
            'patient' => $patient,
            'requests' => $requests,
            'records' => $records
        ]);
    }
    
    /**
     * Create new request (POST)
     */
    public function createRequest() {
        $this->requireAuth('patient');
        
        // Debug block removed to allow saving

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/patient/dashboard');
        }

        // DEBUG TRAP REMOVED
        
        $patientId = $this->getUserId();
        $doctorNameRequested = trim($_POST['doctor_name_requested'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($message)) {
            $this->setFlash('error', 'الرجاء كتابة رسالة');
            $this->redirect('/patient/dashboard');
        }
        
        // Create request
        try {
            Request::create($patientId, $doctorNameRequested, $message);
            $this->setFlash('success', 'تم إرسال الطلب بنجاح');
        } catch (Exception $e) {
            // SHOW ERROR ON SCREEN
            // Store the error in a flash message and redirect back to the dashboard
            $this->setFlash('error', 'فشل الحفظ في قاعدة البيانات: ' . $e->getMessage());
            $this->redirect('/patient/dashboard');
        }
        
        $this->redirect('/patient/dashboard');
    }
}
