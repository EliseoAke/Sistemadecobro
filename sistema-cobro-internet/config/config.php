<?php
// Configuración general de la aplicación
define('BASE_URL', 'http://localhost/sistema-cobro-internet/');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'index');

// Configuración de la zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión
session_start();

// Función para cargar clases automáticamente
spl_autoload_register(function($class) {
    // Convertir namespace a ruta de archivo
    $path = str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
});
?>