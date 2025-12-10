<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'patient') { die("Unauthorized"); }

$patientId = $_SESSION['user_id'];
$doctorId = $_POST['doctor_id'];
$date = $_POST['date'];
$time = $_POST['time'];

$stmt = $pdo->prepare("
    INSERT INTO appointments (patient_id, doctor_id, date, time)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$patientId, $doctorId, $date, $time]);

$notify = $pdo->prepare("
    INSERT INTO notifications (user_id, message)
    VALUES (?, ?)
");
$notify->execute([$doctorId, "لديك حجز موعد جديد من المريض ".$_SESSION['name']]);

header("Location: dashboard_patient.php");
exit;
