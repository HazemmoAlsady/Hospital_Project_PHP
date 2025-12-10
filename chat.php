<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);




require "session_config.php";
require "db.php";

// تأكد من تسجيل الدخول
if (!isset($_SESSION["user_id"])) {
    die("خطأ: لم يتم تسجيل الدخول.");
}

$loggedUser = $_SESSION["user_id"];
$role       = $_SESSION["role"];

// استقبال البيانات من الرابط
$patientId = $_GET["patient_id"] ?? null;
$doctorId  = $_GET["doctor_id"] ?? null;

// ===============================
// تحديد الطرف الآخر الصحيح
// ===============================
if ($role === "doctor") {

    if (!$patientId) {
        die("❌ Doctor Mode Error: يجب إرسال patient_id للصفحة.");
    }

    $otherId = $patientId;
}

else if ($role === "patient") {

    if (!$doctorId) {
        die("❌ Patient Mode Error: يجب إرسال doctor_id للصفحة.");
    }

    $otherId = $doctorId;
}

// ===============================
// جلب الرسائل
// ===============================
$stmt = $pdo->prepare("
    SELECT * FROM messages
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY created_at ASC
");
$stmt->execute([$loggedUser, $otherId, $otherId, $loggedUser]);
$messages = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>المحادثة</title>

<style>
body { background:#ecf0f1; font-family:Cairo; }
.chat-box {
    width:60%; margin:40px auto; background:white;
    padding:20px; border-radius:14px; box-shadow:0 6px 25px rgba(0,0,0,0.12);
}
#messages {
    max-height:400px; min-height:300px; overflow-y:auto;
    padding:10px; background:#f7f7f7; border:1px solid #ddd;
    border-radius:10px;
}
.message { padding:10px 15px; margin:10px 0; border-radius:10px; max-width:70%; }
.me { background:#3498db; color:white; margin-left:auto; }
.other { background:#bdc3c7; }
</style>

</head>
<body>

<div class="chat-box">
<h2 style="text-align:center;">المحادثة</h2>

<div id="messages">
<?php foreach ($messages as $m): ?>
    <div class="message <?= $m["sender_id"] == $loggedUser ? 'me' : 'other' ?>">
        <?= nl2br(htmlspecialchars($m["message"])) ?>
        <br><small><?= $m["created_at"] ?></small>
    </div>
<?php endforeach; ?>
</div>

<form method="POST" action="send_message.php">
    <input type="hidden" name="receiver_id" value="<?= $otherId ?>">
    <input type="text" name="message" placeholder="اكتب رسالتك..." required style="width:80%; padding:10px;">
    <button style="padding:10px 15px;">إرسال</button>
</form>

</div>

<script>
function loadMessages() {
    fetch("fetch_messages.php?other_id=<?= $otherId ?>")
    .then(r => r.text())
    .then(html => {
        let box = document.getElementById("messages");
        box.innerHTML = html;
        box.scrollTop = box.scrollHeight;
    });
}
setInterval(loadMessages, 1000);
loadMessages();
</script>

</body>
</html>
