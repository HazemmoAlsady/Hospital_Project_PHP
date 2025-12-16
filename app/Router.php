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
    
    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Normalize slashes
        $requestUri = preg_replace('#/+#', '/', $requestUri);
        
        // Remove base path (project folder)
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        // Normalize final URI
        $requestUri = '/' . trim($requestUri, '/');
        if ($requestUri === '') $requestUri = '/';
        
        // Match routes
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $requestMethod, $requestUri)) {
                return $this->callController($route);
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
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
