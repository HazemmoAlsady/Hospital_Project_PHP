<?php
// Reply to Request - Redesigned
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الرد على طلب | د. <?= htmlspecialchars($_SESSION['name'] ?? '') ?></title>
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
        
        <!-- Header Info -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #0f172a; font-size: 1.8rem; margin-bottom: 10px;">الرد على استشارة طبية</h1>
            <p style="color: #64748b;">أنت ترد الآن على طلب المريض <strong><?= htmlspecialchars($request['patient_name']) ?></strong></p>
        </div>

        <!-- Patient Message Layout -->
        <div class="patient-message-card">
            <div style="display: flex; gap: 15px; align-items: flex-start;">
                <div style="width: 50px; height: 50px; background: #e0f2fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="font-weight: 700; color: #0f172a; font-size: 1.1rem;"><?= htmlspecialchars($request['patient_name']) ?></span>
                        <span style="font-size: 0.85rem; color: #94a3b8;"><?= date('Y-m-d h:i A', strtotime($request['created_at'])) ?></span>
                    </div>
                    <div style="background: #f1f5f9; padding: 20px; border-radius: 0 16px 16px 16px; position: relative;">
                        <p style="margin: 0; color: #334155; line-height: 1.6; font-size: 1rem;">
                            <?= nl2br(htmlspecialchars($request['message'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="reply-form-card">
            <div style="display: flex; gap: 15px; align-items: flex-start;">
                <div style="width: 50px; height: 50px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 15px 0; font-size: 1.1rem; color: #0f172a;">رد الطبيب</h3>
                    
                    <form method="POST" action="<?= BASE_URL ?>doctor/reply/create">
                        <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                        
                        <div class="form-group">
                            <textarea class="form-control" name="reply" rows="8" required 
                                      placeholder="اكتب تشخيصك أو ردك الطبي هنا..." 
                                      style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 15px; font-size: 1rem; width: 100%; box-sizing: border-box; transition: all 0.3s;"
                                      onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 4px rgba(14, 165, 233, 0.1)';"
                                      onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"></textarea>
                        </div>
                        
                        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px;">
                            <a href="<?= BASE_URL ?>doctor/dashboard" class="btn btn-secondary" style="background: white; color: #64748b; border: 1px solid #cbd5e1;">إلغاء</a>
                            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 1rem;">إرسال الرد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
