<?php
// index.php - Punto de entrada de la aplicación

// Configuración de errores para desarrollo
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Autoload manual para las clases
spl_autoload_register(function ($class) {
    $directories = ['controllers', 'models', 'utils'];
    
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Cargar archivos de configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/routes.php';

// Configurar headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Si estamos en HTTPS, añadir headers de seguridad adicionales
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

try {
    // Inicializar el router y manejar la petición
    $router = new Router();
    $router->handleRequest();
    
} catch (Exception $e) {
    // Log del error
    error_log("Error fatal en index.php: " . $e->getMessage());
    
    if (ENVIRONMENT === 'development') {
        echo "<h1>Error Fatal</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // En producción, mostrar una página de error genérica
        http_response_code(500);
        echo "<h1>Error del Servidor</h1>";
        echo "<p>Ha ocurrido un error interno. Por favor contacte al administrador.</p>";
    }
}

?>