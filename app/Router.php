<?php
/**
 * Simple Router for Pure MVC
 * Routes all requests to appropriate Controllers
 */
class Router {
    
    private $routes = [];
    
    /**
     * Add GET route
     */
    public function get($uri, $controller, $method) {
        $this->addRoute('GET', $uri, $controller, $method);
    }
    
    /**
     * Add POST route
     */
    public function post($uri, $controller, $method) {
        $this->addRoute('POST', $uri, $controller, $method);
    }
    
    /**
     * Add route to routes array
     */
    private function addRoute($httpMethod, $uri, $controller, $method) {
        $this->routes[] = [
            'http_method' => $httpMethod,
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method
        ];
    }
    
    /**
     * Dispatch the request to appropriate controller
     */
    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Dynamically detect base path to strip it from URI
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        // Normalize slashes
        $scriptName = str_replace('\\', '/', $scriptName);
        $requestUri = str_replace('\\', '/', $requestUri);
        
        // Force redirect if double slashes exist in readable URL (and it's not the protocol)
        if (strpos($_SERVER['REQUEST_URI'], '//') !== false) {
             $cleanUri = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
             if ($cleanUri !== $_SERVER['REQUEST_URI']) {
                 header("Location: " . $cleanUri, true, 301);
                 exit;
             }
        }

        // Remove duplicate slashes internally as well
        $requestUri = preg_replace('#/+#', '/', $requestUri);
        
        // Remove duplicate slashes internally as well
        $requestUri = preg_replace('#/+#', '/', $requestUri);
        
        // Remove public/index.php if present in path
        $scriptName = str_replace('/public', '', $scriptName);
        
        // Remove the base path from the URI
        if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
            $requestUri = substr($requestUri, strlen($scriptName));
        }
        
        // Clean the URI
        $requestUri = '/' . trim($requestUri, '/');
        
        // Root path fallback
        if ($requestUri === '') $requestUri = '/';

        if (function_exists('log_debug')) {
            log_debug("Router Processing: Original URI=[" . $_SERVER['REQUEST_URI'] . "] Cleaned Route=[" . $requestUri . "]");
        }
        
        // Debug mode logic...
        
        // Root path
        if ($requestUri === '/' . '/') {
            $requestUri = '/';
        }
        
        // Debug mode
        if (isset($_GET['debug'])) {
            echo "<h3>Debug Info:</h3>";
            echo "<p><strong>Original URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
            echo "<p><strong>Cleaned URI:</strong> {$requestUri}</p>";
            echo "<p><strong>Request Method:</strong> {$requestMethod}</p>";
            echo "<h3>Available Routes:</h3><pre>";
            print_r($this->routes);
            echo "</pre>";
            exit;
        }
        
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $requestMethod, $requestUri)) {
                return $this->callController($route);
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>Requested URI: <strong>{$requestUri}</strong></p>";
        echo "<p>Request Method: <strong>{$requestMethod}</strong></p>";
        echo "<p><a href='/graduation_project/?debug=1'>View Available Routes</a></p>";
    }
    
    /**
     * Check if route matches
     */
    private function matchRoute($route, $requestMethod, $requestUri) {
        if ($route['http_method'] !== $requestMethod) {
            return false;
        }
        
        // Simple match for now
        return $route['uri'] === $requestUri;
    }
    
    /**
     * Call the controller method
     */
    private function callController($route) {
        $controllerName = $route['controller'];
        $methodName = $route['method'];
        
        // Include controller file
        $controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            die("Controller not found: {$controllerName}");
        }
        
        require_once $controllerFile;
        
        // Create controller instance and call method
        $controller = new $controllerName();
        return $controller->$methodName();
    }
    
    /**
     * Get base path from script name
     */
    private function getBasePath() {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return $scriptName === '/' ? '' : $scriptName;
    }
}
