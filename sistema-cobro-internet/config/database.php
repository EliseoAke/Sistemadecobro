<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_cobro_internet');

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                DB_USER,
                DB_PASS,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
    
    // Patrón Singleton para obtener la instancia de la conexión
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Método para ejecutar consultas
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die('Error en la consulta: ' . $e->getMessage());
        }
    }
    
    // Método para obtener un solo registro
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    // Método para obtener todos los registros
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    // Método para insertar registros
    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->query($sql, $data);
        return $this->connection->lastInsertId();
    }
    
    // Método para actualizar registros
    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(function($field) {
            return $field . ' = :' . $field;
        }, array_keys($data));
        
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        $this->query($sql, $params);
    }
    
    // Método para eliminar registros
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $this->query($sql, $params);
    }
}
?>