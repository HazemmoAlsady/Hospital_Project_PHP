<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'session_config.php';
require 'db.php';

// تأكد أن المستخدم مريض
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header('Location: index.php');
    exit;
}

$patientId = $_SESSION['user_id'];

// fetch patient info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$patientId]);
$patient = $stmt->fetch();

// fetch requests + doctor's reply
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS doctor_name
    FROM requests r
    LEFT JOIN users u ON r.doctor_id = u.id
    WHERE r.patient_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$patientId]);
$requests = $stmt->fetchAll();

// fetch medical records
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
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>لوحة المريض</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { background: #e9edf5; font-family: "Cairo", sans-serif; display: flex; justify-content: center; padding-top: 40px; }
    .container { width: 70%; background: white; padding: 40px; border-radius: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    h1, h3 { color: #222; }
    input, textarea { width: 100%; padding: 12px; border: 1px solid #bbb; border-radius: 10px; margin-bottom: 12px; }
    textarea { resize: vertical; }
    button { padding: 12px; background: #3498db; color: white; border: none; border-radius: 10px; font-size: 16px; cursor: pointer; width: 100%; }
    button:hover { background: #217dbb; }
    .request-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 20px; }
    .status { font-weight: bold; }
    .status.accepted { color: green; } .status.pending { color: orange; } .status.rejected { color: red; }
    .reply-box { background: #e8f6ff; border: 1px solid #bcdcff; padding: 12px; border-radius: 10px; margin-top: 10px; }
    .btn-chat { background: #1abc9c; color: white !important; padding: 10px 15px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 15px; display: inline-block; margin-top: 10px; }
    .btn-chat:hover { background: #16a085; }
  </style>
</head>
<body>
<div class="container">
  <div class="topnav" style="text-align:right; margin-bottom:20px;">
    مرحبًا، <?= htmlspecialchars($_SESSION['name']) ?> |
    <a href="logout.php">Logout</a>
  </div>

  <h1 style="text-align:center;">لوحة المريض</h1>

  <h3>معلوماتك</h3>
  <div><strong>الاسم:</strong> <?= htmlspecialchars($patient['name']) ?></div>
  <div><strong>البريد:</strong> <?= htmlspecialchars($patient['email']) ?></div>
  <div><strong>الطبيب المطلوب:</strong> <?= htmlspecialchars($patient['desired_doctor'] ?? '-') ?></div>

  <hr>

  <h3>إرسال طلب لطبيب</h3>

  <form method="post" action="submit_request.php">
    <label>اسم الطبيب المطلوب (اختياري)</label>
    <input type="text" name="doctor_name_requested" placeholder="مثال: د. محمد">

    <label>رسالتك / سبب التواصل</label>
    <textarea name="message" rows="4" required></textarea>

    <button type="submit">إرسال الطلب</button>
  </form>

  <hr>

  <h3>طلباتك السابقة</h3>

  <?php if ($requests): ?>

      <?php foreach($requests as $r): ?>
      <div class="request-card">

          <strong>الطبيب:</strong>
          <?= htmlspecialchars($r['doctor_name_requested'] ?: ($r['doctor_name'] ?? "—")) ?>
          <br><br>

          <strong>رسالة المريض:</strong><br>
          <?= nl2br(htmlspecialchars($r['message'])) ?>
          <br><br>

          <strong>الحالة:</strong>
          <span class="status <?= $r['status'] ?>">
              <?= htmlspecialchars($r['status']) ?>
          </span>

          <br><br>

          <strong>رد الطبيب:</strong><br>

          <?php if (!empty($r['doctor_reply'])): ?>
              <div class="reply-box">
                  <?= nl2br(htmlspecialchars($r['doctor_reply'])) ?>
              </div>
          <?php else: ?>
              <span style="color:#777;">لا يوجد رد حتى الآن</span>
          <?php endif; ?>

          <br><br>

          <!-- زر المحادثة يظهر فقط إذا الطبيب قبل الطلب -->
          <?php if (!empty($r['doctor_id'])): ?>
              <a class="btn-chat" href="chat.php?doctor_id=<?= $r['doctor_id'] ?>">محادثة</a>
          <?php endif; ?>

          <br>
          <small><?= htmlspecialchars($r['created_at']) ?></small>

      </div>
      <?php endforeach; ?>

  <?php else: ?>
      <div>لا توجد طلبات بعد.</div>
  <?php endif; ?>

</div>
</body>
</html>
