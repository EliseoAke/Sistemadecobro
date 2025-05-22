<?php
class PagosController extends Controller {
    private $pagoModel;
    private $clienteModel;
    private $planModel;
    
    public function __construct() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $this->pagoModel = $this->model('Pago');
        $this->clienteModel = $this->model('Cliente');
        $this->planModel = $this->model('Plan');
    }
    
    public function index() {
        $pagos = $this->pagoModel->getPagosConCliente();
        $this->view('pagos/index', ['pagos' => $pagos]);
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar datos
            $cliente_id = $_POST['cliente_id'];
            $monto = floatval($_POST['monto']);
            $metodo_pago = trim($_POST['metodo_pago']);
            $descripcion = trim($_POST['descripcion']);
            
            // Validaciones básicas
            $errores = [];
            
            if (empty($cliente_id)) {
                $errores[] = 'Debe seleccionar un cliente';
            }
            
            if ($monto <= 0) {
                $errores[] = 'El monto debe ser mayor a 0';
            }
            
            if (empty($metodo_pago)) {
                $errores[] = 'Debe seleccionar un método de pago';
            }
            
            if (empty($errores)) {
                $id = $this->pagoModel->registrarPago($cliente_id, $monto, $metodo_pago, $descripcion);
                
                if ($id) {
                    $_SESSION['mensaje'] = 'Pago registrado correctamente';
                    $this->redirect('pagos');
                } else {
                    $errores[] = 'Error al registrar el pago';
                }
            }
            
            $clientes = $this->clienteModel->getClientesConPlanes();
            $this->view('pagos/crear', [
                'errores' => $errores,
                'clientes' => $clientes,
                'pago' => $_POST
            ]);
        } else {
            $clientes = $this->clienteModel->getClientesConPlanes();
            $this->view('pagos/crear', ['clientes' => $clientes]);
        }
    }
    
    public function ver($id) {
        $pago = $this->pagoModel->getById($id);
        
        if ($pago) {
            $cliente = $this->clienteModel->getById($pago['cliente_id']);
            $plan = $this->planModel->getById($cliente['plan_id']);
            
            $this->view('pagos/ver', [
                'pago' => $pago,
                'cliente' => $cliente,
                'plan' => $plan
            ]);
        } else {
            $_SESSION['mensaje'] = 'Pago no encontrado';
            $this->redirect('pagos');
        }
    }
    
    public function eliminar($id) {
        $pago = $this->pagoModel->getById($id);
        
        if ($pago) {
            $result = $this->pagoModel->delete($id);
            
            if ($result) {
                $_SESSION['mensaje'] = 'Pago eliminado correctamente';
            } else {
                $_SESSION['mensaje'] = 'Error al eliminar el pago';
            }
            
            $this->redirect('pagos');
        } else {
            $_SESSION['mensaje'] = 'Pago no encontrado';
            $this->redirect('pagos');
        }
    }
    
    public function porCliente($cliente_id) {
        $cliente = $this->clienteModel->getById($cliente_id);
        
        if ($cliente) {
            $pagos = $this->pagoModel->getPagosPorCliente($cliente_id);
            $this->view('pagos/por_cliente', [
                'pagos' => $pagos,
                'cliente' => $cliente
            ]);
        } else {
            $_SESSION['mensaje'] = 'Cliente no encontrado';
            $this->redirect('clientes');
        }
    }
    
    public function buscarPorFechas() {
        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fecha_inicio = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_fin'];
            
            // Validar fechas
            if (empty($fecha_inicio) || empty($fecha_fin)) {
                $_SESSION['mensaje'] = 'Debe seleccionar ambas fechas';
                $this->redirect('pagos');
            }
            
            $pagos = $this->pagoModel->getPagosPorFechas($fecha_inicio, $fecha_fin);
            $this->view('pagos/por_fechas', [
                'pagos' => $pagos,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            ]);
        } else {
            $this->view('pagos/buscar_por_fechas');
        }
    }
    
    public function generarRecibo($id) {
        $pago = $this->pagoModel->getById($id);
        
        if ($pago) {
            $cliente = $this->clienteModel->getById($pago['cliente_id']);
            $plan = $this->planModel->getById($cliente['plan_id']);
            
            $data = [
                'pago' => $pago,
                'cliente' => $cliente,
                'plan' => $plan
            ];
            
            $this->viewWithoutTemplate('pagos/recibo', $data);
        } else {
            $_SESSION['mensaje'] = 'Pago no encontrado';
            $this->redirect('pagos');
        }
    }
}
?>