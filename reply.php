<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit;
}

$requestId = $_GET['id'] ?? null;

if (!$requestId) {
    die("رقم الطلب غير موجود.");
}

// جلب بيانات الطلب
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS patient_name
    FROM requests r
    JOIN users u ON r.patient_id = u.id
    WHERE r.id = ?
");
$stmt->execute([$requestId]);
$request = $stmt->fetch();

if (!$request) {
    die("الطلب غير موجود.");
}
?>
<!doctype html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>رد على المريض</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #e9edf5;
            font-family: "Cairo", sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 40px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reply-card {
            width: 65%;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #222;
            font-size: 28px;
        }

        .message-box {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        textarea {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bbb;
            resize: vertical;
            outline: none;
            font-size: 16px;
            transition: 0.2s;
        }

        textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52,152,219,0.3);
        }

        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #217dbb;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="reply-card">

    <h1>رد على المريض: <?= htmlspecialchars($request['patient_name']) ?></h1>

    <div class="message-box">
        <p><strong>رسالة المريض:</strong></p>
        <p style="font-size: 18px; margin-top: 5px;">
            <?= htmlspecialchars($request['message']) ?>
        </p>
    </div>

    <form method="POST" action="save_reply.php">
        <input type="hidden" name="id" value="<?= $requestId ?>">

        <label>اكتب الرد:</label>
        <textarea name="reply" rows="5" placeholder="اكتب ردًا واضحًا للمريض..." required></textarea>

        <br><br>
        <button type="submit">إرسال الرد</button>
    </form>

</div>

</body>
</html>
