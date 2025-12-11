<!-- Create Medical Record - Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة سجل طبي</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/forms.css">
</head>
<body>
    <div class="container" style="max-width: 700px; margin-top: 40px;">
        <div class="card">
            <div class="card-header">
                <h2>إضافة سجل طبي للمريض: <?= htmlspecialchars($patient['name']) ?></h2>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>doctor/record/create">
                    <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                    
                    <div class="form-group">
                        <label for="diagnosis">التشخيص:</label>
                        <textarea name="diagnosis" id="diagnosis" rows="3" required placeholder="وصف حالة المريض..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="prescriptions">الأدوية الموصوفة:</label>
                        <textarea name="prescriptions" id="prescriptions" rows="3" required placeholder="قائمة الأدوية والجرعات..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">ملاحظات إضافية (اختياري):</label>
                        <textarea name="notes" id="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="form-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">حفظ السجل</button>
                        <a href="<?= BASE_URL ?>doctor/dashboard" class="btn btn-secondary">عودة</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
