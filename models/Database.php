<?php
// models/Database.php - Clase para conexión y operaciones con base de datos

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $config = getDatabaseConfig();
            
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            if (ENVIRONMENT === 'development') {
                die("Error de conexión: " . $e->getMessage());
            } else {
                die("Error de conexión a la base de datos. Contacte al administrador.");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    // Método para ejecutar consultas SELECT
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en consulta: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Método para ejecutar consultas INSERT, UPDATE, DELETE
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            error_log("Error en ejecución: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Método para obtener el último ID insertado
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    // Método para obtener un solo registro
    public function queryOne($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en consulta única: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Método para iniciar transacción
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    // Método para hacer commit
    public function commit() {
        return $this->pdo->commit();
    }
    
    // Método para hacer rollback
    public function rollback() {
        return $this->pdo->rollback();
    }
    
    // Método para log del sistema
    public function log($user_id, $action, $table_name = null, $record_id = null, $old_values = null, $new_values = null) {
        $sql = "INSERT INTO system_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $user_id,
            $action,
            $table_name,
            $record_id,
            $old_values ? json_encode($old_values) : null,
            $new_values ? json_encode($new_values) : null,
            $_SERVER['REMOTE_ADDR'] ?? null
        ];
        
        $this->execute($sql, $params);
    }
}

?>