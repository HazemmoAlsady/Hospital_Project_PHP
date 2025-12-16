<!-- Login View for Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة المستشفى</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/stylesheets/auth.css">
</head>
<body class="auth-page">

<div class="medical-decoration top-left">🏥</div>
<div class="medical-decoration bottom-right">⚕️</div>

<div class="auth-container">
    <div class="auth-header">
        <div class="auth-icon">🏥</div>
        <h1>تسجيل الدخول</h1>
        <p>مرحباً بك في نظام إدارة المستشفى</p>
    </div>

    <div class="auth-body">
        <?php if (!empty($errors)): ?>
        <div class="auth-errors">
            <ul>
                <?php foreach($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="login" class="auth-form">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="example@hospital.com"
                    value="<?= htmlspecialchars($_SESSION['old_email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="auth-btn">
                🔐 تسجيل الدخول
            </button>
        </form>

        <div class="auth-footer">
            <p>ليس لديك حساب؟ <a href="<?= BASE_URL ?>register">سجل الآن</a></p>
        </div>
    </div>
</div>

</body>
</html>
