<?php
// Punto de entrada de la aplicación
// Carga el sistema de autoload
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Iniciar la aplicación
$app = new App();
?>