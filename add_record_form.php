<?php
session_start();
require 'db.php';

if ($_SESSION['role'] !== 'doctor') { die("Unauthorized"); }

$patientId = $_GET['patient_id'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>إضافة ملف طبي</title>
<style>
body {
    font-family: "Cairo", sans-serif;
    background: #eef1f7;
    padding: 40px;
}
.form-box {
    background: white;
    width: 50%;
    margin: auto;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
input, textarea {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border: 1px solid #bbb;
    border-radius: 10px;
}
button {
    padding: 12px;
    width: 100%;
    border: none;
    background: #3498db;
    color: white;
    border-radius: 10px;
    cursor: pointer;
    font-size: 18px;
}
</style>
</head>
<body>

<div class="form-box">
    <h2>إضافة ملف طبي للمريض رقم: <?= $patientId ?></h2>

    <form method="POST" action="add_medical_record.php">
        <input type="hidden" name="patient_id" value="<?= $patientId ?>">

        <label>التشخيص:</label>
        <textarea name="diagnosis" required></textarea>

        <label>الأدوية:</label>
        <textarea name="prescriptions"></textarea>

        <label>ملاحظات:</label>
        <textarea name="notes"></textarea>

        <button type="submit">حفظ الملف الطبي</button>
    </form>
</div>

</body>
</html>
