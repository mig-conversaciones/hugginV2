<?php
// controllers/DashboardController.php - Controlador del Dashboard

class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Verificar autenticación
        $authController = new AuthController();
        if (!$authController->checkAuth()) {
            return;
        }
        
        $user = Session::getUser();
        $page_title = 'Dashboard';
        
        // Obtener estadísticas básicas para el dashboard
        $stats = $this->getDashboardStats();
        $recent_activities = $this->getRecentActivities();
        $notifications = $this->getNotifications();
        
        // Cargar la vista del dashboard
        include __DIR__ . '/../views/layouts/app.php';
    }
    
    private function getDashboardStats() {
        // Por ahora devolvemos datos de prueba
        // En los siguientes sprints, esto se conectará con datos reales
        return [
            'instructores_activos' => 45,
            'fichas_formacion' => 120,
            'ambientes_disponibles' => 32,
            'alertas_tyt' => 5
        ];
    }
    
    private function getRecentActivities() {
        // Obtener las últimas actividades del sistema
        $sql = "SELECT sl.*, u.nombre_completo 
                FROM system_logs sl 
                LEFT JOIN users u ON sl.user_id = u.id 
                ORDER BY sl.created_at DESC 
                LIMIT 10";
        
        return $this->db->query($sql);
    }
    
    private function getNotifications() {
        // Por ahora devolvemos notificaciones de prueba
        // En siguientes sprints se implementará el sistema completo de notificaciones
        return [
            [
                'type' => 'alert',
                'title' => 'Alerta de Carga Horaria',
                'message' => 'Juan Pérez tiene un déficit de 10 horas esta semana.',
                'time' => '2 horas'
            ],
            [
                'type' => 'info',
                'title' => 'Ficha Habilitada para TyT',
                'message' => 'La ficha 228106 ha alcanzado el 75% evaluado.',
                'time' => '1 día'
            ]
        ];
    }
    
    public function getStatsAjax() {
        $authController = new AuthController();
        if (!$authController->checkAuth()) {
            return;
        }
        
        $stats = $this->getDashboardStats();
        Response::json($stats);
    }
}

?>