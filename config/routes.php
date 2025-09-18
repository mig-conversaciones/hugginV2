<?php
// config/routes.php - Configuración de rutas

class Router {
    private $routes = [];
    private $middlewares = [];
    
    public function __construct() {
        $this->defineRoutes();
    }
    
    private function defineRoutes() {
        // Rutas de autenticación
        $this->routes['GET']['/'] = ['AuthController', 'login'];
        $this->routes['GET']['/auth/login'] = ['AuthController', 'login'];
        $this->routes['POST']['/auth/login'] = ['AuthController', 'login'];
        $this->routes['GET']['/auth/logout'] = ['AuthController', 'logout'];
        $this->routes['GET']['/auth/profile'] = ['AuthController', 'profile'];
        $this->routes['POST']['/auth/profile'] = ['AuthController', 'profile'];
        $this->routes['GET']['/auth/change-password'] = ['AuthController', 'changePassword'];
        $this->routes['POST']['/auth/change-password'] = ['AuthController', 'changePassword'];
        
        // Dashboard
        $this->routes['GET']['/dashboard'] = ['DashboardController', 'index'];
        
        // Middlewares - rutas que requieren autenticación
        $this->middlewares['/dashboard'] = ['auth'];
        $this->middlewares['/auth/profile'] = ['auth'];
        $this->middlewares['/auth/change-password'] = ['auth'];
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remover parámetros GET de la URI
        $uri = strtok($uri, '?');
        
        // Remover la base URL si existe
        $base_path = dirname($_SERVER['SCRIPT_NAME']);
        if ($base_path !== '/' && strpos($uri, $base_path) === 0) {
            $uri = substr($uri, strlen($base_path));
        }
        
        // Asegurar que la URI empiece con /
        $uri = '/' . ltrim($uri, '/');
        
        // Buscar la ruta
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            
            // Ejecutar middlewares si existen
            if (isset($this->middlewares[$uri])) {
                foreach ($this->middlewares[$uri] as $middleware) {
                    if (!$this->executeMiddleware($middleware)) {
                        return;
                    }
                }
            }
            
            // Ejecutar el controlador
            $this->executeController($route[0], $route[1]);
        } else {
            // Ruta no encontrada
            Response::notFound();
        }
    }
    
    private function executeMiddleware($middleware) {
        switch ($middleware) {
            case 'auth':
                $authController = new AuthController();
                return $authController->checkAuth();
            default:
                return true;
        }
    }
    
    private function executeController($controllerName, $method) {
        try {
            // Verificar si el controlador existe
            if (!class_exists($controllerName)) {
                throw new Exception("Controlador $controllerName no encontrado");
            }
            
            $controller = new $controllerName();
            
            // Verificar si el método existe
            if (!method_exists($controller, $method)) {
                throw new Exception("Método $method no encontrado en $controllerName");
            }
            
            // Ejecutar el método
            $controller->$method();
            
        } catch (Exception $e) {
            error_log("Error en router: " . $e->getMessage());
            
            if (ENVIRONMENT === 'development') {
                die("Error: " . $e->getMessage());
            } else {
                Response::notFound();
            }
        }
    }
    
    public function addRoute($method, $uri, $controller, $action, $middlewares = []) {
        $this->routes[$method][$uri] = [$controller, $action];
        
        if (!empty($middlewares)) {
            $this->middlewares[$uri] = $middlewares;
        }
    }
}

?>