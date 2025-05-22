<?php
// Script para gestionar el usuario administrador
// Coloca este archivo en la raíz del proyecto y accede desde el navegador

// Configuración de la base de datos - Ajusta estos valores según tu configuración
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sistema_cobro_internet';

// Variables para mensajes
$messages = [];
$errors = [];
$admin_exists = false;
$admin_info = null;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'test_connection':
                testDatabaseConnection();
                break;
            case 'create_admin':
                createAdminUser($_POST['nombre'], $_POST['email'], $_POST['password']);
                break;
            case 'update_admin':
                updateAdminUser($_POST['id'], $_POST['nombre'], $_POST['email'], $_POST['password']);
                break;
            case 'test_login':
                testLogin($_POST['email'], $_POST['password']);
                break;
        }
    }
}

// Verificar conexión a la base de datos
function testDatabaseConnection() {
    global $db_host, $db_user, $db_pass, $db_name, $messages, $errors;
    
    try {
        $db = new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $messages[] = "✅ Conexión a la base de datos exitosa.";
        
        // Verificar si existe la tabla usuarios
        $stmt = $db->query("SHOW TABLES LIKE 'usuarios'");
        if ($stmt->rowCount() > 0) {
            $messages[] = "✅ La tabla 'usuarios' existe.";
            
            // Verificar la estructura de la tabla
            $stmt = $db->query("DESCRIBE usuarios");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (in_array('fecha_actualizacion', $columns)) {
                $messages[] = "✅ La columna 'fecha_actualizacion' existe en la tabla.";
            } else {
                $messages[] = "ℹ️ La columna 'fecha_actualizacion' no existe en la tabla. No se actualizará este campo.";
            }
            
            checkAdminUser($db);
        } else {
            $errors[] = "❌ La tabla 'usuarios' no existe. Debes importar la base de datos.";
        }
        
    } catch (PDOException $e) {
        $errors[] = "❌ Error de conexión: " . $e->getMessage();
    }
}

// Verificar si existe el usuario administrador
function checkAdminUser($db) {
    global $messages, $errors, $admin_exists, $admin_info;
    
    try {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE rol = 'administrador' LIMIT 1");
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            $admin_exists = true;
            $admin_info = $admin;
            $messages[] = "✅ Usuario administrador encontrado: {$admin['nombre']} ({$admin['email']})";
        } else {
            $errors[] = "❌ No se encontró ningún usuario administrador.";
        }
    } catch (PDOException $e) {
        $errors[] = "❌ Error al verificar el usuario administrador: " . $e->getMessage();
    }
}

// Crear usuario administrador
function createAdminUser($nombre, $email, $password) {
    global $db_host, $db_user, $db_pass, $db_name, $messages, $errors;
    
    if (empty($nombre) || empty($email) || empty($password)) {
        $errors[] = "❌ Todos los campos son obligatorios.";
        return;
    }
    
    try {
        $db = new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verificar si ya existe un usuario con ese email
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "❌ Ya existe un usuario con ese email.";
            return;
        }
        
        // Crear el usuario administrador
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, rol, fecha_creacion) VALUES (:nombre, :email, :password, 'administrador', NOW())");
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        
        $messages[] = "✅ Usuario administrador creado correctamente.";
        testDatabaseConnection(); // Actualizar la información
        
    } catch (PDOException $e) {
        $errors[] = "❌ Error al crear el usuario administrador: " . $e->getMessage();
    }
}

// Actualizar usuario administrador
function updateAdminUser($id, $nombre, $email, $password) {
    global $db_host, $db_user, $db_pass, $db_name, $messages, $errors;
    
    if (empty($id) || empty($nombre) || empty($email)) {
        $errors[] = "❌ El ID, nombre y email son obligatorios.";
        return;
    }
    
    try {
        $db = new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Verificar si existe el usuario
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        if ($stmt->rowCount() === 0) {
            $errors[] = "❌ No se encontró el usuario con ID $id.";
            return;
        }
        
        // Verificar si existe la columna fecha_actualizacion
        $stmt = $db->query("DESCRIBE usuarios");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $has_fecha_actualizacion = in_array('fecha_actualizacion', $columns);
        
        // Actualizar el usuario
        if (!empty($password)) {
            // Si se proporciona una nueva contraseña, actualizarla también
            if ($has_fecha_actualizacion) {
                $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, password = :password, fecha_actualizacion = NOW() WHERE id = :id");
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, password = :password WHERE id = :id");
            }
            
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id' => $id
            ]);
            $messages[] = "✅ Usuario administrador actualizado con nueva contraseña.";
        } else {
            // Si no se proporciona contraseña, mantener la actual
            if ($has_fecha_actualizacion) {
                $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email, fecha_actualizacion = NOW() WHERE id = :id");
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id");
            }
            
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'id' => $id
            ]);
            $messages[] = "✅ Usuario administrador actualizado (contraseña no modificada).";
        }
        
        testDatabaseConnection(); // Actualizar la información
        
    } catch (PDOException $e) {
        $errors[] = "❌ Error al actualizar el usuario administrador: " . $e->getMessage();
    }
}

// Probar inicio de sesión
function testLogin($email, $password) {
    global $db_host, $db_user, $db_pass, $db_name, $messages, $errors;
    
    if (empty($email) || empty($password)) {
        $errors[] = "❌ El email y la contraseña son obligatorios.";
        return;
    }
    
    try {
        $db = new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_pass,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Buscar el usuario
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($password, $usuario['password'])) {
                $messages[] = "✅ Inicio de sesión exitoso. Las credenciales son correctas.";
                $messages[] = "✅ Ahora puedes usar estas credenciales en el sistema.";
            } else {
                $errors[] = "❌ Contraseña incorrecta.";
                
                // Mostrar el hash de la contraseña para depuración
                $messages[] = "ℹ️ Hash almacenado: " . $usuario['password'];
                $messages[] = "ℹ️ Hash de la contraseña ingresada: " . password_hash($password, PASSWORD_DEFAULT) . " (Nota: Este hash cambia cada vez)";
            }
        } else {
            $errors[] = "❌ No se encontró ningún usuario con el email: $email";
        }
        
    } catch (PDOException $e) {
        $errors[] = "❌ Error al probar el inicio de sesión: " . $e->getMessage();
    }
}

// Ejecutar verificación inicial
testDatabaseConnection();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Administrador - Sistema de Cobro de Internet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-primary">
                <i class="fas fa-wifi me-2"></i> Sistema de Cobro de Internet
            </h1>
            <h2 class="mb-4">Configuración de Usuario Administrador</h2>
        </div>
        
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
        
        <!-- Tarjeta de conexión a la base de datos -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-database me-2"></i> Conexión a la Base de Datos
            </div>
            <div class="card-body">
                <form method="post" class="mb-3">
                    <input type="hidden" name="action" value="test_connection">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="db_host" class="form-label">Host</label>
                                <input type="text" class="form-control" id="db_host" value="<?= $db_host ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="db_name" class="form-label">Base de datos</label>
                                <input type="text" class="form-control" id="db_name" value="<?= $db_name ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="db_user" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="db_user" value="<?= $db_user ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="db_pass" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="db_pass" value="<?= $db_pass ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i> Probar Conexión
                    </button>
                </form>
                
                <div class="alert alert-info">
                    <p class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Si necesitas cambiar la configuración de la base de datos, edita las variables al inicio de este archivo.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de gestión de usuario administrador -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user-shield me-2"></i> Gestión de Usuario Administrador
            </div>
            <div class="card-body">
                <?php if ($admin_exists): ?>
                    <!-- Formulario para actualizar administrador -->
                    <h5 class="card-title">Actualizar Usuario Administrador</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="update_admin">
                        <input type="hidden" name="id" value="<?= $admin_info['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $admin_info['nombre'] ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= $admin_info['email'] ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para mantener la actual">
                            <div class="form-text">Si no deseas cambiar la contraseña, deja este campo en blanco.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Usuario
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Formulario para crear administrador -->
                    <h5 class="card-title">Crear Usuario Administrador</h5>
                    <form method="post">
                        <input type="hidden" name="action" value="create_admin">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="Administrador" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="admin@sistema.com" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" value="admin123" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i> Crear Usuario
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tarjeta de prueba de inicio de sesión -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-sign-in-alt me-2"></i> Probar Inicio de Sesión
            </div>
            <div class="card-body">
                <h5 class="card-title">Verificar Credenciales</h5>
                <form method="post">
                    <input type="hidden" name="action" value="test_login">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="login_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="login_email" name="email" value="<?= $admin_exists ? $admin_info['email'] : 'admin@sistema.com' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="login_password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="login_password" name="password" value="admin123" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i> Probar Credenciales
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Enlaces -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-link me-2"></i> Enlaces Útiles
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="index.php" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-home me-2"></i> Ir al Sistema
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="http://localhost/phpmyadmin" target="_blank" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-database me-2"></i> Abrir phpMyAdmin
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 text-muted">
            <p>Sistema de Cobro de Internet &copy; <?= date('Y') ?></p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>