<?php
class ClientesController extends Controller {
    private $clienteModel;
    private $planModel;
    private $pagoModel;
    
    public function __construct() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $this->clienteModel = $this->model('Cliente');
        $this->planModel = $this->model('Plan');
        $this->pagoModel = $this->model('Pago');
    }
    
    // Método para mostrar todos los clientes
    public function index() {
        $clientes = $this->clienteModel->getAll();
        $this->view('clientes/index', ['clientes' => $clientes]);
    }
    
    // Método para mostrar el formulario de creación
    public function crear() {
        $planes = $this->planModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario
            $cliente = [
                'nombre' => trim($_POST['nombre']),
                'telefono' => trim($_POST['telefono']),
                'direccion' => trim($_POST['direccion']),
                'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                'plan_id' => $_POST['plan_id'],
                'fecha_instalacion' => $_POST['fecha_instalacion'],
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'notas' => !empty($_POST['notas']) ? trim($_POST['notas']) : null
            ];
            
            // Validar datos
            $errores = $this->validarCliente($cliente);
            
            if (empty($errores)) {
                // Guardar el cliente
                $id = $this->clienteModel->create($cliente);
                
                if ($id) {
                    $_SESSION['mensaje'] = 'Cliente registrado correctamente';
                    $this->redirect('clientes');
                } else {
                    $errores[] = 'Error al registrar el cliente';
                }
            }
            
            $this->view('clientes/crear', [
                'cliente' => $cliente,
                'planes' => $planes,
                'errores' => $errores
            ]);
        } else {
            $this->view('clientes/crear', ['planes' => $planes]);
        }
    }
    
    // Método para mostrar el formulario de edición
    public function editar($id = null) {
        if ($id === null) {
            $this->redirect('clientes');
        }
        
        $cliente = $this->clienteModel->getById($id);
        
        if (!$cliente) {
            $_SESSION['mensaje'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }
        
        $planes = $this->planModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario
            $cliente_data = [
                'nombre' => trim($_POST['nombre']),
                'telefono' => trim($_POST['telefono']),
                'direccion' => trim($_POST['direccion']),
                'email' => !empty($_POST['email']) ? trim($_POST['email']) : null,
                'plan_id' => $_POST['plan_id'],
                'fecha_instalacion' => $_POST['fecha_instalacion'],
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'notas' => !empty($_POST['notas']) ? trim($_POST['notas']) : null
            ];
            
            // Validar datos
            $errores = $this->validarCliente($cliente_data);
            
            if (empty($errores)) {
                // Actualizar el cliente
                if ($this->clienteModel->update($id, $cliente_data)) {
                    $_SESSION['mensaje'] = 'Cliente actualizado correctamente';
                    $this->redirect('clientes');
                } else {
                    $errores[] = 'Error al actualizar el cliente';
                }
            }
            
            // Si hay errores, volver a mostrar el formulario con los datos y errores
            $cliente = array_merge(['id' => $id], $cliente_data);
            $this->view('clientes/editar', [
                'cliente' => $cliente,
                'planes' => $planes,
                'errores' => $errores
            ]);
        } else {
            $this->view('clientes/editar', [
                'cliente' => $cliente,
                'planes' => $planes
            ]);
        }
    }
    
    // Método para ver detalles de un cliente
    public function ver($id = null) {
        if ($id === null) {
            $this->redirect('clientes');
        }
        
        $cliente = $this->clienteModel->getById($id);
        
        if (!$cliente) {
            $_SESSION['mensaje'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }
        
        $plan = $this->planModel->getById($cliente['plan_id']);
        $pagos = $this->pagoModel->getByClienteId($id);
        
        $this->view('clientes/ver', [
            'cliente' => $cliente,
            'plan' => $plan,
            'pagos' => $pagos
        ]);
    }
    
    // Método para eliminar un cliente
    public function eliminar($id = null) {
        if ($id === null) {
            $this->redirect('clientes');
        }
        
        // Verificar si el cliente existe
        $cliente = $this->clienteModel->getById($id);
        
        if (!$cliente) {
            $_SESSION['mensaje'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }
        
        // Verificar si tiene pagos asociados
        $pagos = $this->pagoModel->getByClienteId($id);
        
        if (!empty($pagos)) {
            $_SESSION['mensaje'] = 'No se puede eliminar el cliente porque tiene pagos asociados';
            $this->redirect('clientes');
        }
        
        // Eliminar el cliente
        if ($this->clienteModel->delete($id)) {
            $_SESSION['mensaje'] = 'Cliente eliminado correctamente';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar el cliente';
        }
        
        $this->redirect('clientes');
    }
    
    // Método para obtener información del plan de un cliente (para AJAX)
    public function getPlanInfo($cliente_id = null) {
        header('Content-Type: application/json');
        
        if (!$cliente_id || !is_numeric($cliente_id)) {
            echo json_encode(['success' => false, 'message' => 'ID de cliente no válido']);
            return;
        }
        
        $cliente = $this->clienteModel->getById($cliente_id);
        
        if (!$cliente) {
            echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
            return;
        }
        
        if (!isset($cliente['plan_id']) || !$cliente['plan_id']) {
            echo json_encode(['success' => false, 'message' => 'Cliente sin plan asignado']);
            return;
        }
        
        $plan = $this->planModel->getById($cliente['plan_id']);
        
        if (!$plan) {
            echo json_encode(['success' => false, 'message' => 'Plan no encontrado']);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'plan' => [
                'id' => $plan['id'],
                'nombre' => $plan['nombre'],
                'velocidad' => $plan['velocidad'],
                'precio' => $plan['precio']
            ]
        ]);
    }
    
    // Método para validar los datos del cliente
    private function validarCliente($cliente) {
        $errores = [];
        
        if (empty($cliente['nombre'])) {
            $errores[] = 'El nombre es obligatorio';
        }
        
        if (empty($cliente['telefono'])) {
            $errores[] = 'El teléfono es obligatorio';
        }
        
        if (empty($cliente['direccion'])) {
            $errores[] = 'La dirección es obligatoria';
        }
        
        if (empty($cliente['plan_id'])) {
            $errores[] = 'Debe seleccionar un plan';
        }
        
        if (empty($cliente['fecha_instalacion'])) {
            $errores[] = 'La fecha de instalación es obligatoria';
        }
        
        if (!empty($cliente['email']) && !filter_var($cliente['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido';
        }
        
        return $errores;
    }
}
?>