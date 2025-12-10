<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'session_config.php';
require 'db.php';

// تأكد أن المستخدم دكتور
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: index.php');
    exit;
}

$doctorId = $_SESSION['user_id'];

// fetch doctor info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$doctorId]);
$doctor = $stmt->fetch();

// fetch requests for this doctor
$stmt = $pdo->prepare("
    SELECT r.*, u.name AS patient_name, u.id AS patient_id
    FROM requests r
    JOIN users u ON r.patient_id = u.id
    WHERE r.doctor_id IS NULL OR r.doctor_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$doctorId]);
$requests = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>لوحة الطبيب</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* (احتفظت بنفس الـ CSS اللي عندك) */
    body { background: #ecf0f5; font-family: "Cairo", sans-serif; }
    .container { width: 80%; margin: auto; background: #fff; padding: 30px; border-radius: 16px; margin-top: 40px; box-shadow: 0 6px 25px rgba(0,0,0,0.12); }
    .request-card { background: #fff; border-radius: 12px; padding: 15px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); animation: fadeIn 0.6s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .status { font-weight: bold; } .accepted { color: green; } .rejected { color: red; } .pending { color: #777; }
    .btn { padding: 6px 12px; border-radius: 6px; color: #fff !important; margin-right: 6px; font-size: 15px; transition: 0.2s; text-decoration: none; }
    .btn-accept { background: #27ae60; } .btn-accept:hover { background: #219150; }
    .btn-reject { background: #e74c3c; } .btn-reject:hover { background: #c0392b; }
    .btn-reply { background: #3498db; } .btn-reply:hover { background: #2980b9; }
    .btn-add { background: #8e44ad; } .btn-add:hover { background: #732d91; }
    .btn-view { background: #f39c12; } .btn-view:hover { background: #d68910; }
    .btn-chat { background: #1abc9c; } .btn-chat:hover { background: #16a085; }
    hr { margin: 30px 0; }
  </style>
</head>
<body>
<div class="container">
  <div class="topnav" style="text-align:left;">
    مرحبًا دكتور <?= htmlspecialchars($_SESSION['name']) ?> |
    <a href="logout.php">Logout</a>
  </div>

  <h1 style="text-align:center;">لوحة الطبيب</h1>

  <h3>معلوماتك الشخصية</h3>
  <p><strong>الاسم:</strong> <?= htmlspecialchars($doctor['name']) ?></p>
  <p><strong>التخصص:</strong> <?= htmlspecialchars($doctor['specialization'] ?? '-') ?></p>

  <hr>
  <h3 style="text-align:center;">طلبات المرضى</h3>

  <?php if ($requests): ?>
      <?php foreach ($requests as $r): ?>
      <div class="request-card">
          <p><strong>المريض:</strong> <?= htmlspecialchars($r['patient_name']) ?></p>
          <p><strong>الرسالة:</strong> <?= htmlspecialchars($r['message']) ?></p>
          <p><strong>الحالة:</strong>
              <span class="status <?= $r['status'] === 'accepted' ? 'accepted' : ($r['status'] === 'rejected' ? 'rejected' : 'pending') ?>">
                  <?= htmlspecialchars($r['status']) ?>
              </span>
          </p>

          <?php if (!empty($r['doctor_reply'])): ?>
              <p><strong>ردّك السابق:</strong>
                  <span style="color:#34495e;"><?= nl2br(htmlspecialchars($r['doctor_reply'])) ?></span>
              </p>
          <?php endif; ?>

          <div style="margin-top:10px;">
              <a class="btn btn-accept" href="update_request.php?id=<?= $r['id'] ?>&action=accept">قبول</a>
              <a class="btn btn-reject" href="update_request.php?id=<?= $r['id'] ?>&action=reject">رفض</a>
              <a class="btn btn-reply" href="reply.php?id=<?= $r['id'] ?>">رد</a>

              <!-- محادثة (ترسل patient_id) -->
              <a class="btn btn-chat" 
                href="chat.php?patient_id=<?= urlencode($r['patient_id']) ?>">
                محادثة مع <?= htmlspecialchars($r['patient_name']) ?>
              </a>

              <!-- زر إضافة ملف طبي -->
              <a class="btn btn-add" href="add_record_form.php?patient_id=<?= $r['patient_id'] ?>">إضافة ملف طبي</a>

              <!-- زر عرض السجل الطبي -->
              <a class="btn btn-view" href="view_records.php?patient_id=<?= $r['patient_id'] ?>">عرض السجل الطبي</a>
          </div>
      </div>
      <?php endforeach; ?>
  <?php else: ?>
      <div style="text-align:center; color:#777;">لا توجد طلبات حالياً.</div>
  <?php endif; ?>

</div>
</body>
</html>
