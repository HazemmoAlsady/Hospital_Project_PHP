<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'doctor') { die("Unauthorized"); }

$patientId = $_POST['patient_id'];
$diagnosis = $_POST['diagnosis'];
$prescriptions = $_POST['prescriptions'];
$notes = $_POST['notes'];

$stmt = $pdo->prepare("
    INSERT INTO medical_records (patient_id, doctor_id, diagnosis, prescriptions, notes)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$patientId, $_SESSION['user_id'], $diagnosis, $prescriptions, $notes]);

$notify = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
$notify->execute([$patientId, "تم إضافة ملف طبي جديد لك من الدكتور ".$_SESSION['name']]);

header("Location: dashboard_doctor.php");
exit;
