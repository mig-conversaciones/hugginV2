<?php
// config/database.php - Configuración de base de datos

$database_config = [
    'development' => [
        'host' => 'localhost',
        'port' => '3306',
        'dbname' => 'huggin_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    ],
    'production' => [
        'host' => 'localhost', // Cambiar en producción
        'port' => '3306',
        'dbname' => 'huggin_prod',
        'username' => 'huggin_user',
        'password' => 'password_seguro',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    ]
];

// Retornar configuración según el entorno
function getDatabaseConfig() {
    global $database_config;
    return $database_config[ENVIRONMENT];
}

?>