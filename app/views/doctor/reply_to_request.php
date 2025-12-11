<!-- Reply to Request - Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الرد على الطلب</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/forms.css">
</head>
<body>
    <div class="container" style="max-width: 600px; margin-top: 50px;">
        <div class="card">
            <div class="card-header">
                <h2>الرد على طلب المريض: <?= htmlspecialchars($request['patient_name']) ?></h2>
            </div>
            <div class="card-body">
                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <strong style="color: var(--primary-color);">رسالة المريض:</strong>
                    <p style="margin-top: 5px;"><?= nl2br(htmlspecialchars($request['message'])) ?></p>
                </div>
                
                <form method="POST" action="<?= BASE_URL ?>doctor/reply/create">
                    <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                    
                    <div class="form-group">
                        <label for="reply">الرد:</label>
                        <textarea class="form-control" name="reply" id="reply" rows="5" required placeholder="اكتب ردك هنا..."></textarea>
                    </div>
                    
                    <div class="form-actions" style="margin-top: 20px; display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">إرسال الرد</button>
                        <a href="<?= BASE_URL ?>doctor/dashboard" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
