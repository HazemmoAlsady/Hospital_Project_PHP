<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/MedicalRecord.php';
require_once __DIR__ . '/../models/Message.php';

/**
 * DoctorController - Pure MVC
 * Handles all doctor-related actions
 */
class DoctorController extends Controller {
    
    /**
     * Show doctor dashboard
     */
    public function dashboard() {
        $this->requireAuth('doctor');
        
        $doctorId = $this->getUserId();
        
        // Get data from Models
        $doctor = User::find($doctorId);
        
        $myRequests = Request::getByDoctor($doctorId);
        $pendingRequests = Request::getPending();
        
        // Merge pending requests with doctor's requests
        $requests = array_merge($pendingRequests, $myRequests);
        
        // Sort by created_at desc
        usort($requests, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        $records = MedicalRecord::getByDoctor($doctorId);
        $messages = Message::getRecentByUser($doctorId);
        
        // Calculate stats
        $pendingCount = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
        $acceptedCount = count(array_filter($requests, fn($r) => $r['status'] === 'accepted'));
        $rejectedCount = count(array_filter($requests, fn($r) => $r['status'] === 'rejected'));
        
        // Pass data to view
        $this->view('doctor/dashboard', [
            'doctor' => $doctor,
            'requests' => $requests,
            'records' => $records,
            'messages' => $messages,
            'totalRequests' => count($requests),
            'pendingCount' => $pendingCount,
            'acceptedCount' => $acceptedCount,
            'rejectedCount' => $rejectedCount
        ]);
    }
    
    /**
     * Show reply to request form
     */
    public function replyToRequest() {
        $this->requireAuth('doctor');
        
        $requestId = $_GET['id'] ?? null;
        
        if (!$requestId) {
            $this->redirect('/doctor/dashboard');
        }
        
        $request = Request::find($requestId);
        
        if (!$request) {
            $this->redirect('/doctor/dashboard');
        }
        
        $this->view('doctor/reply_to_request', [
            'request' => $request
        ]);
    }
    
    /**
     * Update request status (accept/reject)
     */
    public function updateRequest() {
        $this->requireAuth('doctor');
        
        $requestId = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;
        
        if (!$requestId || !in_array($action, ['accept', 'reject'])) {
            $this->redirect('/doctor/dashboard');
        }
        
        $doctorId = $this->getUserId();
        $status = $action === 'accept' ? 'accepted' : 'rejected';
        
        Request::updateStatus($requestId, $status, $doctorId);
        
        $this->setFlash('success', 'تم تحديث الطلب بنجاح');
        $this->redirect('/doctor/dashboard');
    }
    
    /**
     * Create reply to request
     */
    public function createReply() {
        $this->requireAuth('doctor');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/doctor/dashboard');
        }
        
        $requestId = $_POST['request_id'] ?? null;
        $reply = trim($_POST['reply'] ?? '');
        
        if (!$requestId || empty($reply)) {
            $this->setFlash('error', 'الرجاء كتابة رد');
            $this->redirect('/doctor/dashboard');
        }
        
        $doctorId = $this->getUserId();
        Request::addReply($requestId, $reply);
        
        $this->setFlash('success', 'تم إرسال الرد بنجاح');
        $this->redirect('/doctor/dashboard');
    }
    
    /**
     * Show create medical record form
     */
    public function showCreateRecord() {
        $this->requireAuth('doctor');
        
        $patientId = $_GET['patient_id'] ?? null;
        
        if (!$patientId) {
            $this->redirect('/doctor/dashboard');
        }
        
        $patient = User::find($patientId);
        
        $this->view('doctor/create_medical_record', [
            'patient' => $patient
        ]);
    }
    
    /**
     * Create medical record
     */
    public function createMedicalRecord() {
        $this->requireAuth('doctor');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/doctor/dashboard');
        }
        
        $patientId = $_POST['patient_id'] ?? null;
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $prescriptions = trim($_POST['prescriptions'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        
        if (!$patientId || empty($diagnosis) || empty($prescriptions)) {
            $this->setFlash('error', 'الرجاء ملء جميع الحقول المطلوبة');
            $this->redirect('/doctor/dashboard');
        }
        
        $doctorId = $this->getUserId();
        MedicalRecord::create($patientId, $doctorId, $diagnosis, $prescriptions, $notes);
        
        $this->setFlash('success', 'تم إضافة السجل الطبي بنجاح');
        $this->redirect('/doctor/dashboard');
    }
    
    /**
     * Show medical records for patient
     */
    public function showMedicalRecords() {
        $this->requireAuth('doctor');
        
        $patientId = $_GET['patient_id'] ?? null;
        
        if (!$patientId) {
            $this->redirect('/doctor/dashboard');
        }
        
        $patient = User::find($patientId);
        $records = MedicalRecord::getByPatient($patientId);
        
        $this->view('doctor/show_medical_records', [
            'patient' => $patient,
            'records' => $records
        ]);
    }
}
