<!-- Doctor Dashboard - Pure MVC -->
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>لوحة الطبيب</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/dashboard.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/dashboard_doctor.css">
</head>
<body>

<!-- Sidebar -->
<aside class="medical-sidebar">
  <div class="sidebar-brand">
    <h2>لوحة الطبيب</h2>
    <p>نظام إدارة المرضى</p>
  </div>
  
  <div class="user-profile">
    <div class="profile-card">
      <div class="profile-avatar doc">
        <?= mb_substr($doctor['name'], 0, 1) ?>
      </div>
      <div class="profile-info">
        <h3>د. <?= htmlspecialchars($doctor['name'] ?? '') ?></h3>
        <p><?= htmlspecialchars($doctor['specialization'] ?? 'عام') ?></p>
      </div>
    </div>
  </div>
  
  <nav class="nav-menu">
    <a href="#overview" class="nav-item active" onclick="showSection('overview')">
      <span class="nav-icon"></span>
      <span>نظرة عامة</span>
    </a>
    <a href="#requests" class="nav-item" onclick="showSection('requests')">
      <span class="nav-icon"></span>
      <span>الطلبات</span>
    </a>
    <a href="#records" class="nav-item" onclick="showSection('records')">
      <span class="nav-icon"></span>
      <span>السجلات الطبية</span>
    </a>
    <a href="#chat" class="nav-item" onclick="showSection('chat')">
      <span class="nav-icon"></span>
      <span>المحادثات</span>
    </a>
  </nav>
  
  <a href="<?= BASE_URL ?>logout" class="logout-button" onclick="event.preventDefault(); window.location.href='<?= BASE_URL ?>logout?' + new Date().getTime();">
    <span></span>
    <span>تسجيل خروج</span>
  </a>
</aside>

<!-- Main Content -->
<main class="main-container">
  
  <!-- Header -->
  <div class="page-title">
    <h1>مرحباً، د. <?= htmlspecialchars($doctor['name']) ?></h1>
    <p>إليك نظرة عامة على طلبات المرضى والسجلات الطبية</p>
  </div>
  
  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap teal"></div>
      <div class="stat-content">
        <h3>إجمالي الطلبات</h3>
        <div class="stat-number"><?= $totalRequests ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap yellow"></div>
      <div class="stat-content">
        <h3>قيد الانتظار</h3>
        <div class="stat-number"><?= $pendingCount ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap green"></div>
      <div class="stat-content">
        <h3>تم القبول</h3>
        <div class="stat-number"><?= $acceptedCount ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap red"></div>
      <div class="stat-content">
        <h3>تم الرفض</h3>
        <div class="stat-number"><?= $rejectedCount ?></div>
      </div>
    </div>
  </div>
  
  <!-- Content Sections -->
  <div id="overview" class="content-section active">
    <!-- Same as requests mostly but simplified or just welcome -->
    <div class="section-card">
      <div class="section-header">
        <h2>مهامك الحالية</h2>
      </div>
      <div style="padding: 25px;">
        <?php 
          $pendingRequests = array_filter($requests, function($r) { return $r['status'] === 'pending'; });
        ?>
        
        <?php if (!empty($pendingRequests)): ?>
          <h3 style="margin-bottom: 15px;">طلبات تنتظر الرد (<?= count($pendingRequests) ?>)</h3>
          <div class="requests-grid">
            <?php 
              // Show first 3 only
              $count = 0;
              foreach ($pendingRequests as $r): 
                if ($count >= 3) break; 
                $count++;
            ?>
              <div class="request-card-doc">
                <div class="req-header">
                  <h4><?= htmlspecialchars($r['patient_name']) ?></h4>
                  <span class="date">منذ <?= round((time() - strtotime($r['created_at'])) / 3600) ?> ساعة</span>
                </div>
                <div class="req-body">
                  <p><?= mb_strimwidth(htmlspecialchars($r['message']), 0, 150, "...") ?></p>
                </div>
                <div class="req-actions">
                  <a href="<?= BASE_URL ?>doctor/request/reply?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">
                    <span></span> الرد على الطلب
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($pendingRequests) > 3): ?>
            <div style="text-align: center; margin-top: 25px;">
              <a href="#requests" onclick="showSection('requests')" class="btn btn-secondary">عرض كل الطلبات</a>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="empty-state">
            <span style="font-size: 50px; display:block; margin-bottom:10px;"></span>
            <h3>رائع! لا توجد طلبات معلقة.</h3>
            <p style="color:#666;">يمكنك الاسترخاء قليلاً أو مراجعة السجلات القديمة.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Requests Section -->
  <div id="requests" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>طلبات المرضى الجديدة</h2>
      </div>
      <div style="padding: 25px;">
        <?php if ($requests): ?>
          <div class="requests-grid">
            <?php foreach ($requests as $r): ?>
              <?php if ($r['status'] === 'pending'): ?>
              <div class="request-card-doc">
                <div class="req-header">
                  <h4><?= htmlspecialchars($r['patient_name']) ?></h4>
                  <div style="display:flex; gap:10px; align-items:center;">
                    <span class="date"><?= date('Y-m-d', strtotime($r['created_at'])) ?></span>
                    <span class="badg pending">قيد الانتظار</span>
                  </div>
                </div>
                <div class="req-body">
                  <p><?= nl2br(htmlspecialchars($r['message'])) ?></p>
                </div>
                <div class="req-actions">
                  <a href="<?= BASE_URL ?>doctor/request/update?id=<?= $r['id'] ?>&action=accept" class="btn btn-success">
                    <span></span> قبول
                  </a>
                  <a href="<?= BASE_URL ?>doctor/request/update?id=<?= $r['id'] ?>&action=reject" class="btn btn-danger">
                    <span></span> رفض
                  </a>
                  <a href="<?= BASE_URL ?>doctor/request/reply?id=<?= $r['id'] ?>" class="btn btn-primary">
                    <span></span> رد
                  </a>
                  <a href="<?= BASE_URL ?>chat?patient_id=<?= $r['patient_id'] ?>" class="btn btn-info" style="background-color: #0ea5e9; color: white;">
                    <span></span> محادثة
                  </a>
                </div>
              </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
          
          <!-- History Table -->
          <h3 style="margin-top: 30px; margin-bottom: 15px; color: var(--text-primary);">سجل الطلبات السابق</h3>
          <table class="medical-table">
            <thead>
              <tr>
                <th>المريض</th>
                <th>التاريخ</th>
                <th>الحالة</th>
                <th>الرسالة</th>
                <th>رد الطبيب</th>
                <th>إجراءات</th> <!-- Added Action Header -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $r): ?>
                <?php if ($r['status'] !== 'pending'): ?>
                <tr>
                  <td><?= htmlspecialchars($r['patient_name']) ?></td>
                  <td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
                  <td><span class="badge <?= $r['status'] ?>"><?= $r['status'] ?></span></td>
                  <td><?= mb_strimwidth(htmlspecialchars($r['message']), 0, 50, "...") ?></td>
                  <td><?= mb_strimwidth(htmlspecialchars($r['doctor_reply'] ?? ''), 0, 50, "...") ?></td>
                  <td>
                    <a href="<?= BASE_URL ?>doctor/request/reply?id=<?= $r['id'] ?>" class="btn btn-sm btn-primary">تعديل الرد</a>
                    <a href="<?= BASE_URL ?>chat?patient_id=<?= $r['patient_id'] ?>" class="btn btn-sm btn-info" style="background-color: #0ea5e9; color: white;">محادثة</a>
                  </td>
                </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="empty-text">لا توجد طلبات حالياً</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Medical Records Section -->
  <div id="records" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>إدارة السجلات الطبية</h2>
      </div>
      <div style="padding: 25px;">
        <!-- List of patients to add records for -->
        <h3 style="margin-bottom: 15px;">اختر مريضاً لإضافة/عرض سجل</h3>
        <!-- This is a simplification, ideally we list unique patients from requests -->
        <?php 
          $uniquePatients = [];
          foreach($requests as $r) {
            $uniquePatients[$r['patient_id']] = $r['patient_name'];
          }
        ?>
        <div class="patient-list">
          <?php foreach($uniquePatients as $pid => $pname): ?>
            <div class="patient-item" style="display:flex; justify-content:space-between; padding:15px; border-bottom:1px solid #eee; align-items:center;">
              <strong><?= htmlspecialchars($pname) ?></strong>
              <div>
                <a href="<?= BASE_URL ?>doctor/record/create?patient_id=<?= $pid ?>" class="btn btn-sm btn-primary">إضافة سجل</a>
                <a href="<?= BASE_URL ?>doctor/records/show?patient_id=<?= $pid ?>" class="btn btn-sm btn-secondary">عرض السجلات</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Chat Section -->
  <div id="chat" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>المحادثات الأخيرة</h2>
      </div>
      <div style="padding: 25px;">
        <?php if ($messages): ?>
          <div class="messages-list">
            <?php foreach ($messages as $msg): ?>
              <?php 
                $otherName = $msg['sender_id'] == $_SESSION['user_id'] 
                                ? ($msg['receiver_name'] ?? 'مستخدم غير معروف') 
                                : ($msg['sender_name'] ?? 'مستخدم غير معروف');
                                
                $otherId = $msg['sender_id'] == $_SESSION['user_id'] 
                                ? ($msg['receiver_id'] ?? 0) 
                                : ($msg['sender_id'] ?? 0);
              ?>
              <a href="<?= BASE_URL ?>chat?patient_id=<?= $otherId ?>" class="message-item" style="display:block; padding:15px; border-bottom:1px solid #eee; text-decoration:none; color:inherit;">
                <div style="display:flex; justify-content:space-between;">
                  <strong><?= htmlspecialchars($otherName) ?></strong>
                  <span style="font-size:12px; color:#999;"><?= date('Y-m-d H:i', strtotime($msg['created_at'])) ?></span>
                </div>
                <p style="margin:5px 0 0; color:#666;"><?= htmlspecialchars($msg['message']) ?></p>
              </a>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="empty-text">لا توجد محادثات سابقة</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</main>

<script>
  // Simple Tab Switching Logic
  function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    
    // Show target section
    document.getElementById(sectionId).style.display = 'block';
    
    // Activate nav item
    const navLink = document.querySelector(`a[href="#${sectionId}"]`);
    if(navLink) navLink.classList.add('active');
    
    // Update URL hash without scroll
    history.replaceState(null, null, `#${sectionId}`);
  }

  // Handle Initial Load
  window.addEventListener('load', () => {
    const hash = window.location.hash.substring(1);
    if(hash && document.getElementById(hash)) {
        showSection(hash);
    } else {
        showSection('overview');
    }
  });
</script>

<style>
  .content-section { display: none; }
  .content-section.active { display: block; }
</style>

</body>
</html>

