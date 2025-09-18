<?php
// controllers/AuthController.php - Controlador de Autenticación

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        // Si ya está logueado, redirigir al dashboard
        if (Session::isLoggedIn()) {
            Response::redirect('/dashboard');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $this->showLoginForm();
        }
    }
    
    private function processLogin() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validación básica
        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Email y contraseña son requeridos');
            $this->showLoginForm();
            return;
        }
        
        // Intentar autenticación
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            // Login exitoso
            Session::setUser($user);
            Session::setFlash('success', '¡Bienvenido al sistema Huggin!');
            
            // Redirigir según el rol
            $redirect_url = $this->getRedirectUrlByRole($user['rol']);
            Response::redirect($redirect_url);
        } else {
            // Login fallido
            Session::setFlash('error', 'Credenciales incorrectas');
            $this->showLoginForm();
        }
    }
    
    private function showLoginForm() {
        $page_title = 'Iniciar Sesión';
        $error_message = Session::getFlash('error');
        $success_message = Session::getFlash('success');
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    private function getRedirectUrlByRole($role) {
        switch ($role) {
            case 'administrador':
            case 'coordinador':
                return '/dashboard';
            case 'instructor':
                return '/programacion';
            case 'auditor':
                return '/reportes';
            default:
                return '/dashboard';
        }
    }
    
    public function logout() {
        Session::logout();
        Session::setFlash('success', 'Has cerrado sesión correctamente');
        Response::redirect('/auth/login');
    }
    
    public function checkAuth() {
        if (!Session::isLoggedIn()) {
            if (Response::isAjaxRequest()) {
                Response::error('Sesión expirada', 401);
            } else {
                Session::setFlash('error', 'Debes iniciar sesión para acceder');
                Response::redirect('/auth/login');
            }
            return false;
        }
        
        // Verificar timeout de sesión
        if (!Session::checkTimeout()) {
            Session::setFlash('error', 'Tu sesión ha expirado');
            Response::redirect('/auth/login');
            return false;
        }
        
        return true;
    }
    
    public function requireRole($required_role) {
        if (!$this->checkAuth()) {
            return false;
        }
        
        if (!Session::hasPermission($required_role)) {
            if (Response::isAjaxRequest()) {
                Response::error('No tienes permisos suficientes', 403);
            } else {
                Session::setFlash('error', 'No tienes permisos para acceder a esta sección');
                Response::forbidden();
            }
            return false;
        }
        
        return true;
    }
    
    public function profile() {
        if (!$this->checkAuth()) {
            return;
        }
        
        $user = Session::getUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
        } else {
            $page_title = 'Mi Perfil';
            include __DIR__ . '/../views/auth/profile.php';
        }
    }
    
    private function updateProfile() {
        $user_id = Session::getUserId();
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        // Validar datos
        $errors = $this->userModel->validateUserData($data, true, $user_id);
        
        if (empty($errors)) {
            if ($this->userModel->update($user_id, $data)) {
                // Actualizar datos en sesión
                Session::set('user_name', $data['nombre_completo']);
                Session::set('user_email', $data['email']);
                
                Session::setFlash('success', 'Perfil actualizado correctamente');
            } else {
                Session::setFlash('error', 'Error al actualizar el perfil');
            }
        } else {
            Session::setFlash('error', 'Por favor corrige los errores');
            Session::set('form_errors', $errors);
        }
        
        Response::redirect('/auth/profile');
    }
    
    public function changePassword() {
        if (!$this->checkAuth()) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validaciones
            $errors = [];
            
            if (empty($current_password)) {
                $errors['current_password'] = 'La contraseña actual es requerida';
            }
            
            if (empty($new_password) || strlen($new_password) < 6) {
                $errors['new_password'] = 'La nueva contraseña debe tener al menos 6 caracteres';
            }
            
            if ($new_password !== $confirm_password) {
                $errors['confirm_password'] = 'Las contraseñas no coinciden';
            }
            
            if (empty($errors)) {
                // Verificar contraseña actual
                $user = $this->userModel->findById(Session::getUserId());
                
                if (password_verify($current_password, $user['password'])) {
                    if ($this->userModel->updatePassword($user['id'], $new_password)) {
                        Session::setFlash('success', 'Contraseña cambiada correctamente');
                        Response::redirect('/auth/profile');
                    } else {
                        $errors['general'] = 'Error al cambiar la contraseña';
                    }
                } else {
                    $errors['current_password'] = 'La contraseña actual es incorrecta';
                }
            }
            
            if (!empty($errors)) {
                Session::setFlash('error', 'Por favor corrige los errores');
                Session::set('form_errors', $errors);
            }
        }
        
        $page_title = 'Cambiar Contraseña';
        include __DIR__ . '/../views/auth/change_password.php';
    }
}