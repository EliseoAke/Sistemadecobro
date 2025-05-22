<?php
class Cliente extends Model {
    public function __construct() {
        parent::__construct();
    }
    
    // Obtener todos los clientes con información del plan
    public function getAll() {
        try {
            $query = "
                SELECT c.*, p.nombre as plan_nombre, p.precio as plan_precio
                FROM clientes c
                LEFT JOIN planes p ON c.plan_id = p.id
                ORDER BY c.nombre ASC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Cliente::getAll: ' . $e->getMessage());
            return [];
        }
    }
    
    // Obtener un cliente por su ID
    public function getById($id) {
        try {
            $query = "
                SELECT c.*, p.nombre as plan_nombre, p.precio as plan_precio
                FROM clientes c
                LEFT JOIN planes p ON c.plan_id = p.id
                WHERE c.id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Cliente::getById: ' . $e->getMessage());
            return false;
        }
    }
    
    // Crear un nuevo cliente
    public function create($cliente) {
        try {
            // Verificar si existe la tabla clientes
            $stmt = $this->db->query("SHOW TABLES LIKE 'clientes'");
            if ($stmt->rowCount() === 0) {
                error_log('Error en Cliente::create: La tabla clientes no existe');
                return false;
            }
            
            // Verificar la estructura de la tabla
            $stmt = $this->db->query("DESCRIBE clientes");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Construir la consulta SQL basada en las columnas existentes
            $fields = [];
            $placeholders = [];
            $bindings = [];
            
            // Campos obligatorios
            $fields[] = 'nombre';
            $placeholders[] = ':nombre';
            $bindings[':nombre'] = $cliente['nombre'];
            
            $fields[] = 'telefono';
            $placeholders[] = ':telefono';
            $bindings[':telefono'] = $cliente['telefono'];
            
            $fields[] = 'direccion';
            $placeholders[] = ':direccion';
            $bindings[':direccion'] = $cliente['direccion'];
            
            $fields[] = 'plan_id';
            $placeholders[] = ':plan_id';
            $bindings[':plan_id'] = $cliente['plan_id'];
            
            $fields[] = 'fecha_instalacion';
            $placeholders[] = ':fecha_instalacion';
            $bindings[':fecha_instalacion'] = $cliente['fecha_instalacion'];
            
            // Campos opcionales
            if (in_array('email', $columns)) {
                $fields[] = 'email';
                $placeholders[] = ':email';
                $bindings[':email'] = $cliente['email'];
            }
            
            if (in_array('activo', $columns)) {
                $fields[] = 'activo';
                $placeholders[] = ':activo';
                $bindings[':activo'] = $cliente['activo'];
            }
            
            if (in_array('notas', $columns)) {
                $fields[] = 'notas';
                $placeholders[] = ':notas';
                $bindings[':notas'] = $cliente['notas'];
            }
            
            if (in_array('fecha_creacion', $columns)) {
                $fields[] = 'fecha_creacion';
                $placeholders[] = 'NOW()';
            }
            
            // Construir la consulta SQL
            $query = "
                INSERT INTO clientes (" . implode(', ', $fields) . ")
                VALUES (" . implode(', ', $placeholders) . ")
            ";
            
            // Eliminar 'NOW()' de los bindings ya que es una función SQL
            $bindings = array_filter($bindings, function($key) {
                return $key !== 'NOW()';
            }, ARRAY_FILTER_USE_KEY);
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error en Cliente::create: ' . $e->getMessage());
            return false;
        }
    }
    
    // Actualizar un cliente existente
    public function update($id, $data) {
        try {
            // Verificar si existe la tabla clientes
            $stmt = $this->db->query("SHOW TABLES LIKE 'clientes'");
            if ($stmt->rowCount() === 0) {
                error_log('Error en Cliente::update: La tabla clientes no existe');
                return false;
            }
            
            // Verificar la estructura de la tabla
            $stmt = $this->db->query("DESCRIBE clientes");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Construir la consulta SQL basada en las columnas existentes
            $sets = [];
            $bindings = [];
            
            // Campos obligatorios
            $sets[] = 'nombre = :nombre';
            $bindings[':nombre'] = $data['nombre'];
            
            $sets[] = 'telefono = :telefono';
            $bindings[':telefono'] = $data['telefono'];
            
            $sets[] = 'direccion = :direccion';
            $bindings[':direccion'] = $data['direccion'];
            
            $sets[] = 'plan_id = :plan_id';
            $bindings[':plan_id'] = $data['plan_id'];
            
            $sets[] = 'fecha_instalacion = :fecha_instalacion';
            $bindings[':fecha_instalacion'] = $data['fecha_instalacion'];
            
            // Campos opcionales
            if (in_array('email', $columns)) {
                $sets[] = 'email = :email';
                $bindings[':email'] = $data['email'];
            }
            
            if (in_array('activo', $columns)) {
                $sets[] = 'activo = :activo';
                $bindings[':activo'] = $data['activo'];
            }
            
            if (in_array('notas', $columns)) {
                $sets[] = 'notas = :notas';
                $bindings[':notas'] = $data['notas'];
            }
            
            if (in_array('fecha_actualizacion', $columns)) {
                $sets[] = 'fecha_actualizacion = NOW()';
            }
            
            // Agregar el ID para la cláusula WHERE
            $bindings[':id'] = $id;
            
            // Construir la consulta SQL
            $query = "
                UPDATE clientes
                SET " . implode(', ', $sets) . "
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($bindings);
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Cliente::update: ' . $e->getMessage() . ' - Query: ' . $query);
            return false;
        }
    }
    
    // Eliminar un cliente
    public function delete($id) {
        try {
            $query = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            error_log('Error en Cliente::delete: ' . $e->getMessage());
            return false;
        }
    }
    
    // Buscar clientes por nombre o teléfono
    public function search($term) {
        try {
            $term = "%$term%";
            $query = "
                SELECT c.*, p.nombre as plan_nombre, p.precio as plan_precio
                FROM clientes c
                LEFT JOIN planes p ON c.plan_id = p.id
                WHERE c.nombre LIKE :term OR c.telefono LIKE :term
                ORDER BY c.nombre ASC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':term', $term, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Cliente::search: ' . $e->getMessage());
            return [];
        }
    }
    
    // Obtener clientes con pagos pendientes
    public function getClientesPendientes($dias = 30) {
        try {
            $query = "
                SELECT c.*, p.nombre as plan_nombre, p.precio as plan_precio,
                       MAX(pg.fecha_pago) as fecha_ultimo_pago,
                       DATEDIFF(CURRENT_DATE, MAX(pg.fecha_pago)) as dias_transcurridos
                FROM clientes c
                LEFT JOIN planes p ON c.plan_id = p.id
                LEFT JOIN pagos pg ON c.id = pg.cliente_id
                WHERE c.activo = 1
                GROUP BY c.id
                HAVING dias_transcurridos >= :dias OR dias_transcurridos IS NULL
                ORDER BY dias_transcurridos DESC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':dias', $dias, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en Cliente::getClientesPendientes: ' . $e->getMessage());
            return [];
        }
    }
    
    // Contar clientes activos
    public function countActive() {
        try {
            $query = "SELECT COUNT(*) FROM clientes WHERE activo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en Cliente::countActive: ' . $e->getMessage());
            return 0;
        }
    }
    
    // Contar todos los clientes
    public function count() {
        try {
            $query = "SELECT COUNT(*) FROM clientes";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error en Cliente::count: ' . $e->getMessage());
            return 0;
        }
    }
}
?>