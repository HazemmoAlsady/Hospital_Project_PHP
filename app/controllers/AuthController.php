<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../config/database.php';

/**
 * AuthController - Pure MVC
 * Handles authentication (login, logout, register)
 */
class AuthController extends Controller {
    
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    /**
     * Show login form
     */
    public function showLogin() {
        // If already logged in, redirect to dashboard
        if ($this->checkAuth()) {
            $this->redirectToDashboard();
        }
        
        $this->view('auth/login', [
            'errors' => $_SESSION['errors'] ?? []
        ]);
        
        unset($_SESSION['errors']);
        unset($_SESSION['old_email']);
    }
    
    /**
     * Process login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        if (!$email || !$password) {
            $errors[] = "من فضلك أدخل البريد الإلكتروني وكلمة المرور.";
        } else {
            require_once __DIR__ . '/../models/User.php';
            $user = User::findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                
                // Explicitly save session before redirect
                session_write_close();
                
                $this->redirectToDashboard();
            } else {
                $errors[] = "البريد الإلكتروني أو كلمة المرور خاطئة.";
            }
        }
        
        $_SESSION['errors'] = $errors;
        // Save old email to repopulate form
        $_SESSION['old_email'] = $email;
        session_write_close();
        
        $this->redirect('/');
    }
    
    /**
     * Logout
     */
    public function logout() {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();
        
        // Redirect to login
        $this->redirect('/');
    }
    
    /**
     * Show register form
     */
    public function showRegister() {
        $this->view('auth/register', [
            'errors' => $_SESSION['errors'] ?? []
        ]);
        
        unset($_SESSION['errors']);
    }
    
    /**
     * Process registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }
        

        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'patient';
        $specialization = trim($_POST['specialization'] ?? '');
        $desired_doctor = trim($_POST['desired_doctor'] ?? '');
        
        $errors = [];
        
        if (!$name || !$email || !$password) {
            $errors[] = "من فضلك املأ جميع الحقول المطلوبة.";
        }
        
        if (empty($errors)) {
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $errors[] = "هذا البريد الإلكتروني مسجل بالفعل.";
            } else {
                try {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $this->pdo->prepare("
                        INSERT INTO users (name, email, password, role, specialization, desired_doctor)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    $stmt->execute([
                        $name, 
                        $email, 
                        $hashed, 
                        $role, 
                        $specialization ?: null, 
                        $desired_doctor ?: null
                    ]);
                    
                    $this->setFlash('success', 'تم التسجيل بنجاح! يمكنك تسجيل الدخول الآن');
                    $this->redirect('/');
                } catch (PDOException $e) {
                    $errors[] = "حدث خطأ في قاعدة البيانات: " . $e->getMessage();
                    // Store the error in a flash message and redirect back to the register page
                    $this->setFlash('error', $e->getMessage());
                    $this->redirect('/register');
                }
            }
        }
        
        $_SESSION['errors'] = $errors;
        session_write_close(); // Ensure session is saved
        $this->redirect('/register');
    }
    
    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard() {
        $role = $_SESSION['role'] ?? null;
        
        switch ($role) {
            case 'admin':
                $this->redirect('/admin/dashboard');
                break;
            case 'doctor':
                $this->redirect('/doctor/dashboard');
                break;
            case 'patient':
                $this->redirect('/patient/dashboard');
                break;
            default:
                $this->redirect('/');
        }
    }

}
