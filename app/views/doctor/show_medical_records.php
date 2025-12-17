<!-- Show Medical Records - Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>السجلات الطبية</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/dashboard.css">
</head>
<body>
    <div class="container" style="margin-top: 40px;">
        <div class="section-card">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>السجلات الطبية للمريض: <?= htmlspecialchars($patient['name']) ?></h2>
                <a href="<?= BASE_URL ?>doctor/dashboard" class="btn btn-secondary">عودة للوحة التحكم</a>
            </div>
            
            <div style="padding: 25px;">
                <?php if ($records): ?>
                <table class="medical-table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الطبيب المعالج</th>
                            <th>التشخيص</th>
                            <th>الأدوية</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?= date('Y-m-d', strtotime($rec['date'])) ?></td>
                            <td>د. <?= htmlspecialchars($rec['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($rec['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($rec['prescriptions']) ?></td>
                            <td><?= htmlspecialchars($rec['notes'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <p>لا توجد سجلات طبية لهذا المريض بعد.</p>
                    <a href="/doctor/record/create?patient_id=<?= $patient['id'] ?>" class="btn btn-primary" style="margin-top: 10px;">إضافة سجل جديد</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
