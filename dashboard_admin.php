<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require "session_config.php";

// تأكد أنه أدمن
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// اجلب كل المستخدمين
$users = $pdo->query("
    SELECT id, name, email, role, specialization, desired_doctor, created_at
    FROM users ORDER BY id DESC
")->fetchAll();

// اجلب الطلبات
$requests = $pdo->query("
    SELECT r.*, u.name AS patient_name
    FROM requests r
    JOIN users u ON u.id = r.patient_id
    ORDER BY r.id DESC
")->fetchAll();

// اجلب السجلات الطبية
$records = $pdo->query("
    SELECT mr.*, 
           p.name AS patient_name,
           d.name AS doctor_name
    FROM medical_records mr
    JOIN users p ON mr.patient_id = p.id
    JOIN users d ON mr.doctor_id = d.id
    ORDER BY mr.id DESC
")->fetchAll();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة التحكم | المدير</title>
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: "Cairo", sans-serif;
    background: #eef1f7;
    padding: 20px;
}
.container {
    width: 90%;
    margin: auto;
}
.card {
    background: white;
    padding: 25px;
    margin-bottom: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
h2 {
    color: #333;
    margin-bottom: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
table th, table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
.role-badge {
    padding: 5px 10px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
}
.admin { background: #8e44ad; }
.doctor { background: #27ae60; }
.patient { background: #2980b9; }
.btn-del {
    color: white;
    background: #e74c3c;
    padding: 5px 10px;
    border-radius: 6px;
    text-decoration: none;
}
.btn-del:hover { background: #c0392b; }
</style>
</head>

<body>

<div class="container">

    <h1 style="text-align:center;">لوحة مدير النظام</h1>
    <p style="text-align:center;">مرحباً، <?= htmlspecialchars($_SESSION['name']) ?></p>
    <div style="text-align:center;margin-bottom:20px;">
        <a href="logout.php">تسجيل خروج</a>
    </div>

    <!-- المستخدمين -->
    <div class="card">
        <h2>جميع المستخدمين</h2>
        <table>
            <tr>
                <th>ID</th><th>الاسم</th><th>البريد</th><th>الدور</th><th>التخصص</th><th>خيارات</th>
            </tr>
            <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <span class="role-badge <?= $u['role'] ?>">
                        <?= $u['role'] ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($u['specialization'] ?: '-') ?></td>
                <td>
                    <a class="btn-del" href="delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('هل تريد حذف هذا المستخدم؟')">حذف</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- الطلبات -->
    <div class="card">
        <h2>طلبات المرضى</h2>
        <table>
            <tr>
                <th>ID</th><th>المريض</th><th>الرسالة</th><th>الحالة</th><th>تاريخ</th>
            </tr>
            <?php foreach ($requests as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['patient_name']) ?></td>
                <td><?= nl2br(htmlspecialchars($r['message'])) ?></td>
                <td><?= $r['status'] ?></td>
                <td><?= $r['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- السجلات الطبية -->
    <div class="card">
        <h2>السجلات الطبية</h2>
        <table>
            <tr>
                <th>ID</th><th>المريض</th><th>الطبيب</th><th>التشخيص</th><th>تاريخ</th>
            </tr>
            <?php foreach ($records as $rec): ?>
            <tr>
                <td><?= $rec['id'] ?></td>
                <td><?= htmlspecialchars($rec['patient_name']) ?></td>
                <td><?= htmlspecialchars($rec['doctor_name']) ?></td>
                <td><?= htmlspecialchars($rec['diagnosis']) ?></td>
                <td><?= $rec['date'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</body>
</html>
