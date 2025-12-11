<!-- Patient Dashboard - Pure MVC -->
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>لوحة المريض</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/dashboard.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/forms.css">
</head>
<body>

<!-- Sidebar -->
<aside class="medical-sidebar">
  <div class="sidebar-brand">
    <h2>🤒 لوحة المريض</h2>
    <p>نظام الرعاية الصحية</p>
  </div>
  
  <div class="user-profile">
    <div class="profile-card">
      <div class="profile-avatar">
        <?= mb_substr($patient['name'], 0, 1) ?>
      </div>
      <div class="profile-info">
        <h3><?= htmlspecialchars($patient['name']) ?></h3>
        <p>مريض</p>
      </div>
    </div>
  </div>
  
  <nav class="nav-menu">
    <a href="<?= BASE_URL ?>patient/dashboard" class="nav-item active">
      <span class="nav-icon">📊</span>
      <span>لوحة التحكم</span>
    </a>
  </nav>
  
  <a href="<?= BASE_URL ?>logout" class="logout-button" onclick="event.preventDefault(); window.location.href='<?= BASE_URL ?>logout?' + new Date().getTime();">
    <span>🚪</span>
    <span>تسجيل خروج</span>
  </a>
</aside>

<!-- Main Content -->
<main class="main-container">

  <!-- DEBUG DATA -->

  
  <!-- Flash Messages -->
  <?php if (isset($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $message): ?>
      <div class="alert alert-<?= $type ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 8px; color: white; background-color: <?= $type == 'error' ? '#ef4444' : '#10b981' ?>;">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>
  
  <!-- Header -->
  <div class="page-title">
    <h1>👋 مرحباً، <?= htmlspecialchars($patient['name']) ?></h1>
    <p>إليك نظرة عامة على طلباتك وسجلاتك الطبية</p>
  </div>
  
  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap teal">📋</div>
      <div class="stat-content">
        <h3>إجمالي الطلبات</h3>
        <div class="stat-number"><?= count($requests) ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap green">📁</div>
      <div class="stat-content">
        <h3>السجلات الطبية</h3>
        <div class="stat-number"><?= count($records) ?></div>
      </div>
    </div>
  </div>
  
  <!-- My Requests Section -->
  <div class="section-card">
    <div class="section-header">
      <h2>📬 طلباتي</h2>
    </div>
    
    <div style="padding: 25px;">
      <?php if ($requests): ?>
        <?php foreach ($requests as $req): ?>
          <div class="request-card">
            <div class="request-header">
              <div>
                <h4>📝 طلب رقم #<?= $req['id'] ?></h4>
                <p>🕐 <?= date('Y-m-d H:i', strtotime($req['created_at'])) ?></p>
              </div>
              <span class="badge <?= $req['status'] ?>">
                <?= $req['status'] ?>
              </span>
            </div>
            
            <div class="request-body">
              <span class="request-label">الرسالة:</span>
              <div class="request-text">
                <?= nl2br(htmlspecialchars($req['message'])) ?>
              </div>
            </div>
            
            <?php if (!empty($req['doctor_reply'])): ?>
              <div class="reply-box">
                <span class="request-label" style="color: var(--success-color);">💬 رد الطبيب:</span>
                <div style="color: var(--text-primary);">
                  <?= nl2br(htmlspecialchars($req['doctor_reply'])) ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state">
          <div class="empty-icon">📭</div>
          <p>لم ترسل أي طلبات بعد</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
  
  <!-- Create New Request Section -->
  <div class="section-card">
    <div class="section-header">
      <h2>📝 إرسال طلب جديد</h2>
    </div>
    <div class="request-form">
      <form method="post" action="<?= BASE_URL ?>patient/request/create">
        <div class="form-group">
          <label>اسم الطبيب المطلوب (اختياري)</label>
          <input type="text" name="doctor_name_requested" placeholder="مثال: د. محمد أحمد">
        </div>
        <div class="form-group">
          <label>رسالتك</label>
          <textarea name="message" placeholder="اكتب رسالتك هنا..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">📤 إرسال الطلب</button>
      </form>
    </div>
  </div>
  
  <!-- Medical Records Section -->
  <?php if ($records): ?>
  <div class="section-card">
    <div class="section-header">
      <h2>📁 سجلاتي الطبية</h2>
    </div>
    
    <table class="medical-table">
      <thead>
        <tr>
          <th>التاريخ</th>
          <th>الطبيب</th>
          <th>التشخيص</th>
          <th>الأدوية</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $rec): ?>
        <tr>
          <td><?= date('Y-m-d', strtotime($rec['date'])) ?></td>
          <td>د. <?= htmlspecialchars($rec['doctor_name']) ?></td>
          <td><?= htmlspecialchars($rec['diagnosis']) ?></td>
          <td><?= htmlspecialchars($rec['prescriptions']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
  
</main>
</body>
</html>
