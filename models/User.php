<?php
// models/User.php - Modelo de Usuario

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ? AND estado = 'activo'";
        $user = $this->db->queryOne($sql, [$email]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Log de login exitoso
            $this->db->log($user['id'], 'login');
            return $user;
        }
        
        // Log de intento de login fallido
        $this->db->log(null, 'failed_login', null, null, null, ['email' => $email]);
        return false;
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND estado = 'activo'";
        return $this->db->queryOne($sql, [$id]);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? AND estado = 'activo'";
        return $this->db->queryOne($sql, [$email]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (nombre_completo, email, password, rol, estado) VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $data['nombre_completo'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol'] ?? 'instructor',
            $data['estado'] ?? 'activo'
        ];
        
        $result = $this->db->execute($sql, $params);
        
        if ($result) {
            $user_id = $this->db->lastInsertId();
            
            // Log de creación
            $this->db->log(Session::getUserId(), 'create_user', 'users', $user_id, null, $data);
            
            return $user_id;
        }
        
        return false;
    }
    
    public function update($id, $data) {
        $user = $this->findById($id);
        if (!$user) {
            return false;
        }
        
        $sql = "UPDATE users SET nombre_completo = ?, email = ?, rol = ?, estado = ?, updated_at = NOW() WHERE id = ?";
        
        $params = [
            $data['nombre_completo'],
            $data['email'],
            $data['rol'],
            $data['estado'],
            $id
        ];
        
        $result = $this->db->execute($sql, $params);
        
        if ($result) {
            // Log de actualización
            $this->db->log(Session::getUserId(), 'update_user', 'users', $id, $user, $data);
        }
        
        return $result;
    }
    
    public function updatePassword($id, $new_password) {
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $result = $this->db->execute($sql, [$hashed_password, $id]);
        
        if ($result) {
            // Log de cambio de contraseña
            $this->db->log(Session::getUserId(), 'change_password', 'users', $id);
        }
        
        return $result;
    }
    
    public function delete($id) {
        $user = $this->findById($id);
        if (!$user) {
            return false;
        }
        
        // En lugar de eliminar, desactivar el usuario
        $sql = "UPDATE users SET estado = 'inactivo', updated_at = NOW() WHERE id = ?";
        $result = $this->db->execute($sql, [$id]);
        
        if ($result) {
            // Log de eliminación
            $this->db->log(Session::getUserId(), 'delete_user', 'users', $id, $user);
        }
        
        return $result;
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT id, nombre_completo, email, rol, estado, created_at, updated_at FROM users WHERE 1=1";
        $params = [];
        
        // Filtros opcionales
        if (isset($filters['rol'])) {
            $sql .= " AND rol = ?";
            $params[] = $filters['rol'];
        }
        
        if (isset($filters['estado'])) {
            $sql .= " AND estado = ?";
            $params[] = $filters['estado'];
        }
        
        if (isset($filters['search'])) {
            $sql .= " AND (nombre_completo LIKE ? OR email LIKE ?)";
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
        }
        
        $sql .= " ORDER BY nombre_completo ASC";
        
        return $this->db->query($sql, $params);
    }
    
    public function validateUserData($data, $is_update = false, $user_id = null) {
        $errors = [];
        
        // Validar nombre completo
        if (empty($data['nombre_completo']) || strlen(trim($data['nombre_completo'])) < 3) {
            $errors['nombre_completo'] = 'El nombre completo es requerido y debe tener al menos 3 caracteres';
        }
        
        // Validar email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email es requerido y debe tener un formato válido';
        } else {
            // Verificar email único
            $existing = $this->findByEmail($data['email']);
            if ($existing && ($is_update === false || $existing['id'] != $user_id)) {
                $errors['email'] = 'Este email ya está registrado en el sistema';
            }
        }
        
        // Validar contraseña (solo en creación o si se proporciona en actualización)
        if (!$is_update) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors['password'] = 'La contraseña es requerida y debe tener al menos 6 caracteres';
            }
        }
        
        // Validar rol
        $valid_roles = ['administrador', 'coordinador', 'instructor', 'auditor'];
        if (empty($data['rol']) || !in_array($data['rol'], $valid_roles)) {
            $errors['rol'] = 'El rol es requerido y debe ser válido';
        }
        
        return $errors;
    }
}

?>