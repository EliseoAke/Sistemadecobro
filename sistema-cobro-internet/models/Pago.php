<?php
class Pago extends Model {
    public function __construct() {
        parent::__construct();
    }
    
    // Obtener todos los pagos
    public function getAll() {
        try {
            $query = "
                SELECT p.*, c.nombre as cliente_nombre, pl.nombre as plan_nombre, pl.precio as plan_precio
                FROM pagos p
                JOIN clientes c ON p.cliente_id = c.id
                JOIN planes pl ON c.plan_id = pl.id
                ORDER BY p.fecha_pago DESC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Pago::getAll: ' . $e->getMessage());
            return [];
        }
    }
    
    // Obtener un pago por su ID
    public function getById($id) {
        try {
            $query = "
                SELECT p.*, c.nombre as cliente_nombre, c.telefono as cliente_telefono, 
                       c.direccion as cliente_direccion, pl.nombre as plan_nombre, 
                       pl.precio as plan_precio, pl.velocidad as plan_velocidad
                FROM pagos p
                JOIN clientes c ON p.cliente_id = c.id
                JOIN planes pl ON c.plan_id = pl.id
                WHERE p.id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Pago::getById: ' . $e->getMessage());
            return false;
        }
    }
    
    // Obtener pagos por cliente
    public function getByClienteId($cliente_id) {
        try {
            $query = "
                SELECT p.*, pl.nombre as plan_nombre, pl.precio as plan_precio
                FROM pagos p
                JOIN clientes c ON p.cliente_id = c.id
                JOIN planes pl ON c.plan_id = pl.id
                WHERE p.cliente_id = :cliente_id
                ORDER BY p.fecha_pago DESC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Pago::getByClienteId: ' . $e->getMessage());
            return [];
        }
    }
    
    // Crear un nuevo pago
    public function create($pago) {
        try {
            $query = "
                INSERT INTO pagos (cliente_id, monto, fecha_pago, periodo, metodo_pago, referencia, notas)
                VALUES (:cliente_id, :monto, :fecha_pago, :periodo, :metodo_pago, :referencia, :notas)
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cliente_id', $pago['cliente_id'], PDO::PARAM_INT);
            $stmt->bindParam(':monto', $pago['monto'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_pago', $pago['fecha_pago'], PDO::PARAM_STR);
            $stmt->bindParam(':periodo', $pago['periodo'], PDO::PARAM_STR);
            $stmt->bindParam(':metodo_pago', $pago['metodo_pago'], PDO::PARAM_STR);
            $stmt->bindParam(':referencia', $pago['referencia'], PDO::PARAM_STR);
            $stmt->bindParam(':notas', $pago['notas'], PDO::PARAM_STR);
            $stmt->execute();
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error en Pago::create: ' . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar un pago existente
    public function update($id, $pago) {
        try {
            $query = "
                UPDATE pagos
                SET monto = :monto,
                    fecha_pago = :fecha_pago,
                    periodo = :periodo,
                    metodo_pago = :metodo_pago,
                    referencia = :referencia,
                    notas = :notas
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':monto', $pago['monto'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_pago', $pago['fecha_pago'], PDO::PARAM_STR);
            $stmt->bindParam(':periodo', $pago['periodo'], PDO::PARAM_STR);
            $stmt->bindParam(':metodo_pago', $pago['metodo_pago'], PDO::PARAM_STR);
            $stmt->bindParam(':referencia', $pago['referencia'], PDO::PARAM_STR);
            $stmt->bindParam(':notas', $pago['notas'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Pago::update: ' . $e->getMessage());
            return false;
        }
    }
    
    // Eliminar un pago
    public function delete($id) {
        try {
            $query = "DELETE FROM pagos WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Pago::delete: ' . $e->getMessage());
            return false;
        }
    }
    
    // Obtener el último pago de un cliente
    public function getUltimoPago($cliente_id) {
        try {
            $query = "
                SELECT * FROM pagos
                WHERE cliente_id = :cliente_id
                ORDER BY fecha_pago DESC
                LIMIT 1
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Pago::getUltimoPago: ' . $e->getMessage());
            return false;
        }
    }
    
    // Verificar si un cliente tiene pagos
    public function clienteTienePagos($cliente_id) {
        try {
            $query = "SELECT COUNT(*) FROM pagos WHERE cliente_id = :cliente_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Error en Pago::clienteTienePagos: ' . $e->getMessage());
            return false;
        }
    }
    
    // Contar todos los pagos
    public function count() {
        try {
            $query = "SELECT COUNT(*) FROM pagos";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en Pago::count: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Obtener ingresos por mes para el gráfico
    public function getIngresosPorMes() {
        try {
            $query = "
                SELECT 
                    DATE_FORMAT(fecha_pago, '%Y-%m') as mes,
                    DATE_FORMAT(fecha_pago, '%b %Y') as mes_nombre,
                    SUM(monto) as total
                FROM pagos
                WHERE fecha_pago >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                GROUP BY mes
                ORDER BY mes ASC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Pago::getIngresosPorMes: ' . $e->getMessage());
            return [];
        }
    }
    
    // Obtener el total de ingresos
    public function getTotalIngresos() {
        try {
            $query = "SELECT SUM(monto) FROM pagos";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            error_log('Error en Pago::getTotalIngresos: ' . $e->getMessage());
            return 0;
        }
    }
}
?>