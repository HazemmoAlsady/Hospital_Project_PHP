<?php
// Create Medical Record - Redesigned
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة سجل طبي | د. <?= htmlspecialchars($_SESSION['name'] ?? '') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/dashboard_doctor.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/forms.css">
</head>
<body style="background-color: #f1f5f9;">

    <!-- Top Navigation -->
    <nav style="background: white; padding: 15px 30px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="<?= BASE_URL ?>doctor/dashboard" style="text-decoration: none; font-size: 20px;"></a>
            <h2 style="margin: 0; font-size: 1.2rem; color: #0f172a;">العودة للوحة التحكم</h2>
        </div>
        <div style="font-weight: bold; color: var(--primary-color);">
            نظام الرعاية الصحية
        </div>
    </nav>

    <div class="reply-container">
        
        <!-- Page Title -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0f172a; font-size: 1.8rem; margin-bottom: 10px;">إضافة سجل طبي جديد</h1>
            <p style="color: #64748b;">تحديث الملف الطبي للمريض: <strong><?= htmlspecialchars($patient['name']) ?></strong></p>
        </div>

        <div class="patient-message-card">
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="width: 50px; height: 50px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    
                </div>
                <div>
                    <h3 style="margin: 0; color: #0f172a;"><?= htmlspecialchars($patient['name']) ?></h3>
                    <p style="margin: 5px 0 0; color: #64748b; font-size: 0.9rem;">
                        <?= htmlspecialchars($patient['email']) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Medical Record Form -->
        <div class="reply-form-card">
            <div style="display: flex; gap: 15px; align-items: flex-start;">
                <div style="width: 50px; height: 50px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    
                </div>
                <div style="flex: 1;">
                    <form method="POST" action="<?= BASE_URL ?>doctor/record/create">
                        <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                        
                        <div class="form-group">
                            <label class="form-label">التشخيص (Diagnosis)</label>
                            <textarea class="form-control" name="diagnosis" rows="3" required 
                                      placeholder="أدخل التشخيص الطبي للحالة..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">الوصفة الطبية (Prescriptions)</label>
                            <textarea class="form-control" name="prescriptions" rows="3" required 
                                      placeholder="قائمة الأدوية والجرعات المطلوبة..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea class="form-control" name="notes" rows="2" 
                                      placeholder="أي ملاحظات أو توصيات أخرى..."></textarea>
                        </div>
                        
                        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px;">
                            <a href="<?= BASE_URL ?>doctor/dashboard" class="btn btn-secondary" style="background: white; color: #64748b; border: 1px solid #cbd5e1;">إلغاء</a>
                            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem;">حفظ السجل</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
