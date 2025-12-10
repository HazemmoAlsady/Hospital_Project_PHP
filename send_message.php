<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "session_config.php";
require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("خطأ: لم يتم تسجيل الدخول.");
}

$sender   = $_SESSION["user_id"];
$role     = $_SESSION["role"];
$receiver = $_POST["receiver_id"] ?? null;
$message  = trim($_POST["message"] ?? "");

if (!$receiver || $message == "") {
    die("خطأ: بيانات الإرسال غير صحيحة.");
}

// حفظ الرسالة
$stmt = $pdo->prepare("
    INSERT INTO messages (sender_id, receiver_id, message)
    VALUES (?, ?, ?)
");
$stmt->execute([$sender, $receiver, $message]);

// إعادة توجيه الشات حسب الدور
if ($role === "doctor") {
    header("Location: chat.php?patient_id=" . $receiver);
} else {
    header("Location: chat.php?doctor_id=" . $receiver);
}

exit;
