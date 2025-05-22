-- Base de datos para el sistema de cobro de internet

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_cobro_internet;
USE sistema_cobro_internet;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'operador') NOT NULL DEFAULT 'operador',
    fecha_creacion DATETIME NOT NULL,
    fecha_actualizacion DATETIME NULL
);

-- Tabla de planes de internet
CREATE TABLE IF NOT EXISTS planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    velocidad VARCHAR(50) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(100) NULL,
    plan_id INT NOT NULL,
    fecha_registro DATETIME NOT NULL,
    fecha_ultimo_pago DATETIME NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (plan_id) REFERENCES planes(id)
);

-- Tabla de pagos
CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATETIME NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    descripcion TEXT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (hash generado con password_hash)
INSERT INTO usuarios (nombre, email, password, rol, fecha_creacion)
VALUES ('Administrador', 'admin@sistema.com', '$2y$10$8tUFBvE7nN.1QWr0iFktGe3HJ5XGlylZq5RWRuGP2eYHkfyXhvuMK', 'administrador', NOW());

-- Insertar algunos planes de ejemplo
INSERT INTO planes (nombre, descripcion, precio, velocidad, activo, fecha_creacion) VALUES
('Básico', 'Plan básico para navegación y redes sociales', 250.00, '5 Mbps', 1, NOW()),
('Estándar', 'Plan para streaming y videollamadas', 350.00, '10 Mbps', 1, NOW()),
('Premium', 'Plan para gaming y trabajo remoto', 500.00, '20 Mbps', 1, NOW()),
('Empresarial', 'Plan para pequeñas empresas', 800.00, '50 Mbps', 1, NOW());