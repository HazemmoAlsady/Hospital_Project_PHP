<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

// لازم يكون أدمن
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("لم يتم تحديد المستخدم المطلوب.");
}

try {
    // حذف المستخدم
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // بعد الحذف — ارجع للوحة التحكم
    header("Location: dashboard_admin.php");
    exit;

} catch (PDOException $e) {

    echo "<h2>حدث خطأ أثناء الحذف:</h2>";
    echo "<pre style='color:red; font-size:18px;'>";
    echo $e->getMessage();
    echo "</pre>";

    echo "<br><a href='dashboard_admin.php'>رجوع</a>";
}
