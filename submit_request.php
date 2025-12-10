<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

// تأكد أن المستخدم مريض فقط
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: index.php');
    exit;
}

$patientId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $doctor_name_requested = trim($_POST['doctor_name_requested'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // رسالة فارغة؟ = رفض
    if (empty($message)) {
        $_SESSION['flash'] = "من فضلك اكتب رسالة قبل الإرسال.";
        header("Location: dashboard_patient.php");
        exit;
    }

    // حفظ الطلب
    $stmt = $pdo->prepare("
        INSERT INTO requests (patient_id, doctor_name_requested, message) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$patientId, $doctor_name_requested ?: null, $message]);

    $_SESSION['flash'] = "تم إرسال طلبك بنجاح.";
    header("Location: dashboard_patient.php");
    exit;
}

// لو حد فتح الصفحة بدون POST
header("Location: dashboard_patient.php");
exit;
