<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = "من فضلك أدخل البريد الإلكتروني وكلمة المرور.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // تسجيل الجلسة
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // التوجيه حسب الدور
            switch ($user['role']) {

                case 'admin':
                    header("Location: dashboard_admin.php");
                    exit;

                case 'doctor':
                    header("Location: dashboard_doctor.php");
                    exit;

                case 'patient':
                    header("Location: dashboard_patient.php");
                    exit;
            }

        } else {
            $errors[] = "البريد الإلكتروني أو كلمة المرور خاطئة.";
        }
    }
}
?>
<!doctype html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #e9edf5;
            font-family: "Cairo", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            width: 60%;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            text-align: center;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }

        input {
            width: 70%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 17px;
        }

        button {
            padding: 12px 25px;
            background: #3498db;
            color: white;
            border: none;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #217dbb;
        }

        .topnav {
            text-align: right;
            margin-bottom: 10px;
        }

        .topnav a {
            font-size: 18px;
            color: #006aff;
            font-weight: bold;
        }

        .notice {
            color: red;
            margin-bottom: 15px;
            font-size: 18px;
        }
    </style>

</head>
<body>

<div class="container">

    <div class="topnav">
        <a href="register.php">Register</a>
    </div>

    <h1>تسجيل الدخول</h1>

    <?php if ($errors): ?>
        <div class="notice">
            <?php foreach ($errors as $e): ?>
                <?= htmlspecialchars($e) ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>البريد الإلكتروني</label><br>
        <input type="email" name="email" required><br>

        <label>كلمة المرور</label><br>
        <input type="password" name="password" required><br>

        <button type="submit">دخول</button>
    </form>

</div>

</body>
</html>
