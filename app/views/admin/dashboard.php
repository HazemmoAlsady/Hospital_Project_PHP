<!-- Admin Dashboard - Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لوحة تحكم المدير</title>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/dashboard.css">
</head>
<body>

<!-- Sidebar -->
<aside class="medical-sidebar">
  <div class="sidebar-brand">
    <h2>⚙️ لوحة المدير</h2>
    <p>إدارة النظام</p>
  </div>
  
  <div class="user-profile">
    <div class="profile-card">
      <div class="profile-avatar adm">
        <?= mb_substr($_SESSION['name'], 0, 1) ?>
      </div>
      <div class="profile-info">
        <h3><?= htmlspecialchars($_SESSION['name']) ?></h3>
        <p>مدير النظام</p>
      </div>
    </div>
  </div>
  
  <nav class="nav-menu">
    <a href="#overview" class="nav-item active" onclick="showSection('overview')">
      <span class="nav-icon">📊</span>
      <span>نظرة عامة</span>
    </a>
    <a href="#users" class="nav-item" onclick="showSection('users')">
      <span class="nav-icon">👥</span>
      <span>المستخدمين</span>
    </a>
    <a href="#requests" class="nav-item" onclick="showSection('requests')">
      <span class="nav-icon">📝</span>
      <span>الطلبات</span>
    </a>
    <a href="#records" class="nav-item" onclick="showSection('records')">
      <span class="nav-icon">📁</span>
      <span>السجلات</span>
    </a>
  </nav>
  
  <a href="<?= BASE_URL ?>logout" class="logout-button" onclick="event.preventDefault(); window.location.href='<?= BASE_URL ?>logout?' + new Date().getTime();">
    <span>🚪</span>
    <span>تسجيل خروج</span>
  </a>
</aside>

<!-- Main Content -->
<main class="main-container">

  <!-- Header -->
  <div class="page-title">
    <h1>لوحة التحكم الرئيسية</h1>
    <p>مرحباً بك في نظام إدارة المستشفى</p>
  </div>
  
  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap teal">👥</div>
      <div class="stat-content">
        <h3>إجمالي المستخدمين</h3>
        <div class="stat-number"><?= $totalUsers ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap green">👨‍⚕️</div>
      <div class="stat-content">
        <h3>الأطباء</h3>
        <div class="stat-number"><?= $doctorCount ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap blue">🤒</div>
      <div class="stat-content">
        <h3>المرضى</h3>
        <div class="stat-number"><?= $patientCount ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon-wrap yellow">📝</div>
      <div class="stat-content">
        <h3>الطلبات</h3>
        <div class="stat-number"><?= $totalRequests ?></div>
      </div>
    </div>
  </div>
  
  <!-- Overview Section -->
  <div id="overview" class="content-section active">
    <div class="section-card">
      <div class="section-header">
        <h2>📌 ملخص سريع</h2>
      </div>
      <div style="padding: 25px;">
        <p>مرحباً بك في لوحة تحكم المدير. يمكنك إدارة المستخدمين، متابعة الطلبات، ومراجعة السجلات الطبية من هنا.</p>
        <div style="display: flex; gap: 15px; margin-top: 20px;">
            <button onclick="showSection('users')" class="btn btn-primary">إدارة المستخدمين</button>
            <button onclick="showSection('requests')" class="btn btn-secondary">مراجعة الطلبات</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Users Management Section -->
  <div id="users" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>👥 إدارة المستخدمين</h2>
      </div>
      <div style="padding: 20px;">
        <table class="medical-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>الاسم</th>
              <th>البريد الإلكتروني</th>
              <th>الدور</th>
              <th>التخصص / الطبيب المفضل</th>
              <th>إجراءات</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td>#<?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <span class="badge role-<?= $u['role'] ?>">
                    <?= $u['role'] === 'doctor' ? 'طبيب' : ($u['role'] === 'admin' ? 'مدير' : 'مريض') ?>
                  </span>
                </td>
                <td>
                  <?php 
                    if ($u['role'] === 'doctor') echo htmlspecialchars($u['specialization'] ?? '-'); 
                    elseif ($u['role'] === 'patient') echo htmlspecialchars($u['desired_doctor'] ?? '-');
                    else echo '-';
                  ?>
                </td>
                <td>
                  <?php if ($u['id'] != $_SESSION['user_id']): ?> <!-- Prevent deleting self -->
                  <a href="<?= BASE_URL ?>admin/user/delete?id=<?= $u['id'] ?>" 
                     onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');"
                     class="btn btn-sm btn-danger">
                     🗑️ حذف
                  </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Requests Section -->
  <div id="requests" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>📝 كل الطلبات</h2>
      </div>
      <div style="padding: 20px;">
        <table class="medical-table">
          <thead>
            <tr>
              <th>رقم الطلب</th>
              <th>المريض</th>
              <th>الطبيب المطلوب</th>
              <th>الرسالة</th>
              <th>الحالة</th>
              <th>التاريخ</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($requests as $r): ?>
              <tr>
                <td>#<?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['patient_name']) ?></td>
                <td><?= htmlspecialchars($r['doctor_name_requested'] ?? 'غير محدد') ?></td>
                <td><?= mb_strimwidth(htmlspecialchars($r['message']), 0, 40, "...") ?></td>
                <td><span class="badge <?= $r['status'] ?>"><?= $r['status'] ?></span></td>
                <td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Records Section -->
  <div id="records" class="content-section">
    <div class="section-card">
      <div class="section-header">
        <h2>📁 كل السجلات الطبية</h2>
      </div>
      <div style="padding: 20px;">
        <table class="medical-table">
          <thead>
            <tr>
              <th>المريض</th>
              <th>الطبيب</th>
              <th>التشخيص</th>
              <th>التاريخ</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($records as $rec): ?>
              <tr>
                <td><?= htmlspecialchars($rec['patient_name'] ?? 'مريض غير موجود') ?></td>
                <td>د. <?= htmlspecialchars($rec['doctor_name'] ?? 'طبيب غير موجود') ?></td>
                <td><?= mb_strimwidth(htmlspecialchars($rec['diagnosis']), 0, 50, "...") ?></td>
                <td><?= date('Y-m-d', strtotime($rec['date'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</main>

<script>
  function showSection(sectionId) {
    document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
    
    document.getElementById(sectionId).style.display = 'block';
    
    const navLink = document.querySelector(`a[href="#${sectionId}"]`);
    if(navLink) navLink.classList.add('active');
    
    history.pushState(null, null, `#${sectionId}`);
  }

  window.addEventListener('load', () => {
    const hash = window.location.hash.substring(1) || 'overview';
    showSection(hash);
  });
</script>

<style>
  .content-section { display: none; }
  .content-section.active { display: block; }
  .badge.role-admin { background: #e0e7ff; color: #3730a3; }
  .badge.role-doctor { background: #dcfce7; color: #166534; }
  .badge.role-patient { background: #fff7ed; color: #9a3412; }
</style>

</body>
</html>
