<?php
class Plan extends Model {
    public function __construct() {
        parent::__construct();
    }
    
    // Obtener todos los planes

    // Añade este método a la clase Plan en models/Plan.php

// Obtener la cantidad de clientes por cada plan
public function getClientesPorPlan() {
    try {
        $query = "
            SELECT p.id, p.nombre, p.precio, p.velocidad, 
                   COUNT(c.id) as total_clientes
            FROM planes p
            LEFT JOIN clientes c ON p.id = c.plan_id AND c.activo = 1
            GROUP BY p.id
            ORDER BY total_clientes DESC
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error en Plan::getClientesPorPlan: ' . $e->getMessage());
        return [];
    }
}
    public function getAll() {
        try {
            $query = "SELECT * FROM planes ORDER BY precio ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Plan::getAll: ' . $e->getMessage());
            return [];
        }
    }
    
    // Obtener un plan por su ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM planes WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Plan::getById: ' . $e->getMessage());
            return false;
        }
    }
    
    // Crear un nuevo plan
    public function create($plan) {
        try {
            $query = "
                INSERT INTO planes (nombre, descripcion, velocidad, precio, activo)
                VALUES (:nombre, :descripcion, :velocidad, :precio, :activo)
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $plan['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $plan['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':velocidad', $plan['velocidad'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $plan['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':activo', $plan['activo'], PDO::PARAM_INT);
            $stmt->execute();
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error en Plan::create: ' . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar un plan existente
    public function update($id, $plan) {
        try {
            $query = "
                UPDATE planes
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    velocidad = :velocidad,
                    precio = :precio,
                    activo = :activo
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nombre', $plan['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $plan['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':velocidad', $plan['velocidad'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $plan['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':activo', $plan['activo'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Plan::update: ' . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar un plan
    public function delete($id) {
        try {
            // Primero verificamos si hay clientes usando este plan
            $query = "SELECT COUNT(*) FROM clientes WHERE plan_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                // Hay clientes usando este plan, no se puede eliminar
                return false;
            }
            
            // Si no hay clientes, procedemos a eliminar
            $query = "DELETE FROM planes WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Plan::delete: ' . $e->getMessage());
            return false;
        }
    }
    
    // Contar planes activos
    public function countActive() {
        try {
            $query = "SELECT COUNT(*) FROM planes WHERE activo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en Plan::countActive: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Contar todos los planes
    public function count() {
        try {
            $query = "SELECT COUNT(*) FROM planes";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en Plan::count: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Obtener planes con la cantidad de clientes que los utilizan
    public function getPlanesConClientes() {
        try {
            $query = "
                SELECT p.*, 
                       COUNT(c.id) as total_clientes,
                       SUM(CASE WHEN c.activo = 1 THEN 1 ELSE 0 END) as clientes_activos
                FROM planes p
                LEFT JOIN clientes c ON p.id = c.plan_id
                GROUP BY p.id
                ORDER BY p.precio ASC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Plan::getPlanesConClientes: ' . $e->getMessage());
            return [];
        }
    }
}
?>