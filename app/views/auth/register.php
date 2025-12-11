<!-- Register View for Pure MVC -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/stylesheets/auth.css">
</head>
<body class="auth-page">

<div class="medical-decoration top-left">🩺</div>
<div class="medical-decoration bottom-right">💊</div>

<div class="auth-container">
    <div class="auth-header">
        <div class="auth-icon">📋</div>
        <h1>تسجيل حساب جديد</h1>
        <p>انضم إلى نظام إدارة المستشفى</p>
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

        <form method="POST" action="register" class="auth-form" id="registerForm">
            <div class="form-group">
                <label for="name">الاسم الكامل</label>
                <input type="text" id="name" name="name" placeholder="أدخل اسمك الكامل" required>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="example@hospital.com" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>اختر نوع الحساب</label>
                <div class="role-selector">
                    <div class="role-option">
                        <input type="radio" id="patient" name="role" value="patient" checked>
                        <label for="patient" class="role-label">
                            <span class="role-icon">🧑‍⚕️</span>
                            <span class="role-name">مريض</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="doctor" name="role" value="doctor">
                        <label for="doctor" class="role-label">
                            <span class="role-icon">👨‍⚕️</span>
                            <span class="role-name">طبيب</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="admin" name="role" value="admin">
                        <label for="admin" class="role-label">
                            <span class="role-icon">⚙️</span>
                            <span class="role-name">مدير</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group conditional-field" id="doctorField">
                <label for="specialization">التخصص الطبي</label>
                <input type="text" id="specialization" name="specialization" placeholder="مثال: طب الأطفال...">
            </div>

            <div class="form-group conditional-field active" id="patientField">
                <label for="desired_doctor">الطبيب المفضل (اختياري)</label>
                <input type="text" id="desired_doctor" name="desired_doctor" placeholder="مثال: د. محمد أحمد">
            </div>

            <button type="submit" class="auth-btn">✅ إنشاء الحساب</button>
        </form>

        <div class="auth-footer">
            <p>لديك حساب بالفعل؟ <a href="<?= BASE_URL ?>">سجل الدخول</a></p>
        </div>
    </div>
</div>

<script>
const roleInputs = document.querySelectorAll('input[name="role"]');
const doctorField = document.getElementById('doctorField');
const patientField = document.getElementById('patientField');

roleInputs.forEach(input => {
    input.addEventListener('change', function() {
        doctorField.classList.remove('active');
        patientField.classList.remove('active');
        
        if (this.value === 'doctor') {
            doctorField.classList.add('active');
        } else if (this.value === 'patient') {
            patientField.classList.add('active');
        }
    });
});
</script>

</body>
</html>
