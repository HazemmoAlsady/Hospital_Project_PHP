<?php
session_start();
require "session_config.php";
require "db.php";
// السماح للطبيب فقط
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "doctor") {
    header("Location: index.php");
    exit;
}

$patientId = $_GET["patient_id"] ?? null;

if (!$patientId) {
    die("رقم المريض غير موجود.");
}

// جلب بيانات المريض
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$patientId]);
$patient = $stmt->fetch();

if (!$patient) {
    die("المريض غير موجود.");
}

// جلب السجلات الطبية الخاصة بالمريض
$stmt = $pdo->prepare("
    SELECT mr.*, u.name AS doctor_name
    FROM medical_records mr
    JOIN users u ON mr.doctor_id = u.id
    WHERE mr.patient_id = ?
    ORDER BY mr.date DESC
");
$stmt->execute([$patientId]);
$records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>السجل الطبي للمريض</title>
<style>
body {
    background: #ecf0f1;
    font-family: "Cairo", sans-serif;
    padding-top: 40px;
}
.container {
    width: 70%;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.record {
    background: #fdfdfd;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 5px solid #3498db;
    border-radius: 10px;
}
h2 { text-align: center; }
.back-btn {
    display: inline-block;
    background: #3498db;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-decoration: none;
}
</style>
</head>
<body>

<div class="container">

    <a href="dashboard_doctor.php" class="back-btn">رجوع</a>

    <h2>السجل الطبي للمريض: <?= htmlspecialchars($patient["name"]) ?></h2>

    <?php if ($records): ?>
        <?php foreach ($records as $rec): ?>
            <div class="record">
                <p><strong>الطبيب:</strong> <?= htmlspecialchars($rec["doctor_name"]) ?></p>
                <p><strong>التشخيص:</strong> <?= nl2br(htmlspecialchars($rec["diagnosis"])) ?></p>
                <p><strong>الأدوية:</strong> <?= nl2br(htmlspecialchars($rec["prescriptions"])) ?></p>
                <p><strong>الملاحظات:</strong> <?= nl2br(htmlspecialchars($rec["notes"])) ?></p>
                <p><small><strong>التاريخ:</strong> <?= $rec["date"] ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; color:#777;">لا توجد ملفات طبية لهذا المريض حتى الآن</p>
    <?php endif; ?>

</div>

</body>
</html>
