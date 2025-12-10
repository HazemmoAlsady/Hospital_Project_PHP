<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'patient';
    $specialization = trim($_POST['specialization'] ?? '');
    $desired_doctor = trim($_POST['desired_doctor'] ?? '');

    if (!$name || !$email || !$password) {
        $errors[] = "من فضلك املأ جميع الحقول المطلوبة.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "هذا البريد الإلكتروني مسجل بالفعل.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, role, specialization, desired_doctor)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([$name, $email, $hashed, $role, $specialization ?: null, $desired_doctor ?: null]);

            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل جديد</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #e9edf5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: "Cairo", sans-serif;
        }

        .container {
            width: 60%;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            animation: fadeIn 0.6s ease;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h1 {
            margin-bottom: 25px;
            color: #222;
        }

        label {
            display: block;
            text-align: right;
            margin: 8px 0 3px;
            font-size: 18px;
            font-weight: bold;
        }

        input, select {
            width: 90%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bbb;
            margin-bottom: 15px;
            font-size: 16px;
        }

        input:focus, select:focus {
            border-color: #3498db;
            box-shadow: 0 0 6px rgba(52,152,219,0.4);
            outline: none;
        }

        button {
            width: 92%;
            padding: 14px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }

        button:hover {
            background: #217ccb;
        }

        .topnav {
            text-align: right;
            margin-bottom: 15px;
        }

        .topnav a {
            font-size: 20px;
            color: #006aff;
            font-weight: bold;
            text-decoration: none;
        }

        .notice {
            background: #ffe2e2;
            color: #cc0000;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>

</head>
<body>

<div class="container">

    <div class="topnav">
        <a href="index.php">Login</a>
    </div>

    <h1>تسجيل جديد</h1>

    <?php if ($errors): ?>
        <div class="notice">
            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>الاسم الكامل</label>
        <input type="text" name="name" required>

        <label>البريد الإلكتروني</label>
        <input type="email" name="email" required>

        <label>كلمة المرور</label>
        <input type="password" name="password" required>

        <label>الدور</label>
        <select name="role" id="role" onchange="toggleFields()">
            <option value="patient">مريض</option>
            <option value="doctor">طبيب</option>
        </select>

        <div id="doctorFields" style="display:none;">
            <label>التخصص (للطبيب)</label>
            <input type="text" name="specialization" placeholder="مثال: باطنة، قلب، عظام">
        </div>

        <div id="patientFields">
            <label>لو تريد طبيب معين (اختياري)</label>
            <input type="text" name="desired_doctor" placeholder="مثال: د. محمد أحمد">
        </div>

        <button type="submit">تسجيل</button>
    </form>
</div>

<script>
function toggleFields() {
    const role = document.getElementById('role').value;
    document.getElementById('doctorFields').style.display = (role === 'doctor') ? 'block' : 'none';
    document.getElementById('patientFields').style.display = (role === 'patient') ? 'block' : 'none';
}
toggleFields();
</script>

</body>
</html>
