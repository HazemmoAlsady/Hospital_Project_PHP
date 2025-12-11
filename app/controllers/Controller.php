<?php
/**
 * Base Controller
 * All controllers extend this class
 */
class Controller {
    
    /**
     * Render a view with data
     */
    protected function view($viewPath, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Build view file path
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        
        if (!file_exists($viewFile)) {
            die("View not found: {$viewPath}");
        }
        
        // Include the view
        require $viewFile;
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url) {
        // If url starts with /, prepend BASE_URL
        if (strpos($url, '/') === 0) {
            $baseUrl = defined('BASE_URL') ? BASE_URL : '/';
            // Simple concatenation first
            $url = rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
        }
        
        // Final safety cleanup for double slashes (excluding protocol)
        $url = preg_replace('#([^:])//+#', '$1/', $url);
        
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function checkAuth($role = null) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        if ($role && $_SESSION['role'] !== $role) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Require authentication or redirect
     */
    protected function requireAuth($role = null) {
        // Prevent caching for protected pages
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.

        if (!$this->checkAuth($role)) {
            $this->redirect('/');
        }
    }
    
    /**
     * Get current user ID
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    protected function getRole() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    protected function getFlash($type) {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
