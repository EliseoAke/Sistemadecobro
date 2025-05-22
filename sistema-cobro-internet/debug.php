<?php
// Script para depurar problemas con la base de datos
// Coloca este archivo en la raíz del proyecto y accede desde el navegador

// Configuración de la base de datos - Ajusta estos valores según tu configuración
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sistema_cobro_internet';

// Variables para mensajes
$messages = [];
$errors = [];
$tables = [];
$table_structures = [];

// Verificar conexión a la base de datos
try {
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_name",
        $db_user,
        $db_pass,
        [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $messages[] = "✅ Conexión a la base de datos exitosa.";
    
    // Obtener todas las tablas
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        $messages[] = "✅ Se encontraron " . count($tables) . " tablas en la base de datos.";
        
        // Obtener la estructura de cada tabla
        foreach ($tables as $table) {
            $stmt = $db->query("DESCRIBE $table");
            $table_structures[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        $errors[] = "❌ No se encontraron tablas en la base de datos.";
    }
    
    // Verificar si existen las tablas principales
    $required_tables = ['clientes', 'planes', 'pagos', 'usuarios'];
    foreach ($required_tables as $table) {
        if (in_array($table, $tables)) {
            $messages[] = "✅ La tabla '$table' existe.";
        } else {
            $errors[] = "❌ La tabla '$table' no existe.";
        }
    }
    
    // Verificar permisos de escritura
    try {
        $db->exec("CREATE TEMPORARY TABLE test_write (id INT)");
        $messages[] = "✅ Permisos de escritura verificados correctamente.";
    } catch (PDOException $e) {
        $errors[] = "❌ Error al verificar permisos de escritura: " . $e->getMessage();
    }
    
} catch (PDOException $e) {
    $errors[] = "❌ Error de conexión: " . $e->getMessage();
}

// Verificar permisos de archivos
$file_paths = [
    'controllers/ClientesController.php',
    'models/Cliente.php',
    'views/clientes/crear.php',
    'views/clientes/editar.php'
];

foreach ($file_paths as $path) {
    if (file_exists($path)) {
        if (is_readable($path)) {
            $messages[] = "✅ El archivo '$path' existe y es legible.";
        } else {
            $errors[] = "❌ El archivo '$path' existe pero no es legible.";
        }
    } else {
        $errors[] = "❌ El archivo '$path' no existe.";
    }
}

// Verificar configuración de PHP
$php_settings = [
    'display_errors' => ini_get('display_errors'),
    'error_reporting' => ini_get('error_reporting'),
    'log_errors' => ini_get('log_errors'),
    'error_log' => ini_get('error_log'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit'),
    'post_max_size' => ini_get('post_max_size'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'pdo_mysql' => extension_loaded('pdo_mysql') ? 'Habilitado' : 'No habilitado'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depuración del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .container {
            max-width: 1200px;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Depuración del Sistema de Cobro de Internet</h1>
        
        <!-- Mensajes y errores -->
        <?php if (!empty($messages)): ?>
            <div class="alert alert-success">
                <?php foreach ($messages as $message): ?>
                    <p class="mb-1"><?= $message ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-1"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Estructura de la base de datos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Estructura de la Base de Datos</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($table_structures)): ?>
                    <div class="accordion" id="accordionTables">
                        <?php foreach ($table_structures as $table => $structure): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?= $table ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $table ?>" aria-expanded="false" aria-controls="collapse<?= $table ?>">
                                        Tabla: <?= $table ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $table ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $table ?>" data-bs-parent="#accordionTables">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Campo</th>
                                                        <th>Tipo</th>
                                                        <th>Nulo</th>
                                                        <th>Clave</th>
                                                        <th>Predeterminado</th>
                                                        <th>Extra</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($structure as $column): ?>
                                                        <tr>
                                                            <td><?= $column['Field'] ?></td>
                                                            <td><?= $column['Type'] ?></td>
                                                            <td><?= $column['Null'] ?></td>
                                                            <td><?= $column['Key'] ?></td>
                                                            <td><?= $column['Default'] ?></td>
                                                            <td><?= $column['Extra'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        No se pudo obtener la estructura de las tablas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Configuración de PHP -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Configuración de PHP</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Configuración</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($php_settings as $setting => $value): ?>
                                <tr>
                                    <td><?= $setting ?></td>
                                    <td><?= $value ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Información del sistema -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Sistema</h5>
            </div>
            <div class="card-body">
                <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
                <p><strong>Server Software:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?></p>
                <p><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?></p>
                <p><strong>Script Filename:</strong> <?= $_SERVER['SCRIPT_FILENAME'] ?></p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>