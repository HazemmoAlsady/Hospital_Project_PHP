<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit;
}

$requestId = $_POST['id'] ?? null;
$reply = trim($_POST['reply'] ?? '');

if (!$requestId || !$reply) {
    header("Location: dashboard_doctor.php");
    exit;
}

$stmt = $pdo->prepare("UPDATE requests SET doctor_reply = ?, status = 'accepted' WHERE id = ?");
$stmt->execute([$reply, $requestId]);

header("Location: dashboard_doctor.php");
exit;
