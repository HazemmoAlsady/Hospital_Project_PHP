<?php

// لو السيشن لسه مابدأتش → اضبط الإعدادات قبل البدء
if (session_status() !== PHP_SESSION_ACTIVE) {

    // ضبط إعدادات الجلسة
    ini_set('session.gc_maxlifetime', 21600);
    ini_set('session.cookie_lifetime', 21600);

    session_set_cookie_params([
        'lifetime' => 21600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

// لو السيشن بدأت بالفعل → لا تضبط إعدادات مرة أخرى
// وتجنّب أي تحذيرات
