<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'session_config.php';
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit;
}

$requestId = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;
$doctorId = $_SESSION['user_id'];

if (!$requestId || !$action) {
    header("Location: dashboard_doctor.php");
    exit;
}

if ($action === "accept") {
    // عند القبول: عيّن Doctor وغيّر الحالة
    $stmt = $pdo->prepare("UPDATE requests SET status = ?, doctor_id = ? WHERE id = ?");
    $stmt->execute(['accepted', $doctorId, $requestId]);

} elseif ($action === "reject") {
    $stmt = $pdo->prepare("UPDATE requests SET status = ? WHERE id = ?");
    $stmt->execute(['rejected', $requestId]);
}

header("Location: dashboard_doctor.php");
exit;
