<?php
// utils/Response.php - Manejo de respuestas HTTP

class Response {
    
    public static function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    public static function success($message, $data = null) {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    public static function error($message, $status = 400, $errors = null) {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
    
    public static function redirect($url, $permanent = false) {
        $status = $permanent ? 301 : 302;
        http_response_code($status);
        
        // Si la URL no es absoluta, hacerla relativa a BASE_URL
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        
        header("Location: $url");
        exit();
    }
    
    public static function redirectBack($default = '/') {
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        
        if ($referer && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
            header("Location: $referer");
        } else {
            self::redirect($default);
        }
        exit();
    }
    
    public static function notFound() {
        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
        exit();
    }
    
    public static function unauthorized() {
        http_response_code(401);
        if (self::isAjaxRequest()) {
            self::error('No autorizado', 401);
        } else {
            self::redirect('/auth/login');
        }
    }
    
    public static function forbidden() {
        http_response_code(403);
        if (self::isAjaxRequest()) {
            self::error('Acceso denegado', 403);
        } else {
            include __DIR__ . '/../views/errors/403.php';
            exit();
        }
    }
    
    public static function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    public static function setHeader($name, $value) {
        header("$name: $value");
    }
    
    public static function download($filepath, $filename = null) {
        if (!file_exists($filepath)) {
            self::notFound();
            return;
        }
        
        $filename = $filename ?: basename($filepath);
        
        self::setHeader('Content-Type', 'application/octet-stream');
        self::setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        self::setHeader('Content-Length', filesize($filepath));
        
        readfile($filepath);
        exit();
    }
}

?>