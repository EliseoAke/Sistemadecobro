<?php
// Clase base para todos los modelos
class Model {
    protected $db;
    
    public function __construct() {
        try {
            // Obtener la instancia de conexión a la base de datos
            $this->db = Database::getInstance()->getConnection();
        } catch (PDOException $e) {
            die('Error al conectar con la base de datos: ' . $e->getMessage());
        }
    }
    
    // Método para crear un nuevo registro
    public function create($data) {
        return false;
    }
    
    // Método para obtener todos los registros
    public function getAll() {
        return [];
    }
    
    // Método para obtener un registro por su ID
    public function getById($id) {
        return false;
    }
    
    // Método para actualizar un registro
    public function update($id, $data) {
        return false;
    }
    
    // Método para eliminar un registro
    public function delete($id) {
        return false;
    }
}
?>