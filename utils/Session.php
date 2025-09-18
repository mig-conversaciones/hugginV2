<?php
// utils/Session.php - Manejo de sesiones

class Session {
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }
    
    // Métodos específicos para autenticación
    public static function setUser($user) {
        self::set('user_id', $user['id']);
        self::set('user_name', $user['nombre_completo']);
        self::set('user_email', $user['email']);
        self::set('user_rol', $user['rol']);
        self::set('login_time', time());
        self::set('last_activity', time());
    }
    
    public static function getUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => self::get('user_id'),
            'nombre_completo' => self::get('user_name'),
            'email' => self::get('user_email'),
            'rol' => self::get('user_rol')
        ];
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function getUserRole() {
        return self::get('user_rol');
    }
    
    public static function isLoggedIn() {
        return self::has('user_id') && self::get('user_id') !== null;
    }
    
    public static function checkTimeout() {
        $lastActivity = self::get('last_activity');
        
        if ($lastActivity && (time() - $lastActivity) > SESSION_TIMEOUT) {
            self::logout();
            return false;
        }
        
        // Actualizar última actividad
        self::set('last_activity', time());
        return true;
    }
    
    public static function logout() {
        // Log del logout
        if (self::isLoggedIn()) {
            $db = Database::getInstance();
            $db->log(self::getUserId(), 'logout');
        }
        
        self::destroy();
    }
    
    // Verificar permisos por rol
    public static function hasPermission($required_role) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        $user_role = self::getUserRole();
        
        // Jerarquía de roles
        $roles_hierarchy = [
            'auditor' => 1,
            'instructor' => 2,
            'coordinador' => 3,
            'administrador' => 4
        ];
        
        $user_level = $roles_hierarchy[$user_role] ?? 0;
        $required_level = $roles_hierarchy[$required_role] ?? 5;
        
        return $user_level >= $required_level;
    }
    
    // Mensajes flash
    public static function setFlash($type, $message) {
        self::set('flash_' . $type, $message);
    }
    
    public static function getFlash($type) {
        $message = self::get('flash_' . $type);
        self::remove('flash_' . $type);
        return $message;
    }
    
    public static function hasFlash($type) {
        return self::has('flash_' . $type);
    }
}

?>