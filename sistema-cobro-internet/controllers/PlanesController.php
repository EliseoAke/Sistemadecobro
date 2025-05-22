<?php
class PlanesController extends Controller {
    private $planModel;
    
    public function __construct() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $this->planModel = $this->model('Plan');
    }
    
    // Método para mostrar todos los planes
    public function index() {
        $planes = $this->planModel->getPlanesConClientes();
        $this->view('planes/index', ['planes' => $planes]);
    }
    
    // Método para mostrar el formulario de creación
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario
            $plan = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'velocidad' => trim($_POST['velocidad']),
                'precio' => floatval($_POST['precio']),
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            // Validar datos
            $errores = $this->validarPlan($plan);
            
            if (empty($errores)) {
                // Guardar el plan
                $id = $this->planModel->create($plan);
                
                if ($id) {
                    $_SESSION['mensaje'] = 'Plan creado correctamente';
                    $this->redirect('planes');
                } else {
                    $errores[] = 'Error al crear el plan';
                }
            }
            
            $this->view('planes/crear', [
                'plan' => $plan,
                'errores' => $errores
            ]);
        } else {
            $this->view('planes/crear');
        }
    }
    
    // Método para mostrar el formulario de edición
    public function editar($id = null) {
        if ($id === null) {
            $this->redirect('planes');
        }
        
        $plan = $this->planModel->getById($id);
        
        if (!$plan) {
            $_SESSION['mensaje'] = 'Plan no encontrado';
            $this->redirect('planes');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar el formulario
            $plan_data = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'velocidad' => trim($_POST['velocidad']),
                'precio' => floatval($_POST['precio']),
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            // Validar datos
            $errores = $this->validarPlan($plan_data);
            
            if (empty($errores)) {
                // Actualizar el plan
                if ($this->planModel->update($id, $plan_data)) {
                    $_SESSION['mensaje'] = 'Plan actualizado correctamente';
                    $this->redirect('planes');
                } else {
                    $errores[] = 'Error al actualizar el plan';
                }
            }
            
            // Si hay errores, volver a mostrar el formulario con los datos y errores
            $plan = array_merge(['id' => $id], $plan_data);
            $this->view('planes/editar', [
                'plan' => $plan,
                'errores' => $errores
            ]);
        } else {
            $this->view('planes/editar', ['plan' => $plan]);
        }
    }
    
    // Método para eliminar un plan
    public function eliminar($id = null) {
        if ($id === null) {
            $this->redirect('planes');
        }
        
        // Verificar si el plan existe
        $plan = $this->planModel->getById($id);
        
        if (!$plan) {
            $_SESSION['mensaje'] = 'Plan no encontrado';
            $this->redirect('planes');
        }
        
        // Intentar eliminar el plan
        if ($this->planModel->delete($id)) {
            $_SESSION['mensaje'] = 'Plan eliminado correctamente';
        } else {
            $_SESSION['mensaje'] = 'No se puede eliminar el plan porque hay clientes que lo utilizan';
        }
        
        $this->redirect('planes');
    }
    
    // Método para validar los datos del plan
    private function validarPlan($plan) {
        $errores = [];
        
        if (empty($plan['nombre'])) {
            $errores[] = 'El nombre es obligatorio';
        }
        
        if (empty($plan['velocidad'])) {
            $errores[] = 'La velocidad es obligatoria';
        }
        
        if (empty($plan['precio']) || !is_numeric($plan['precio']) || $plan['precio'] <= 0) {
            $errores[] = 'El precio debe ser un número mayor que cero';
        }
        
        return $errores;
    }
}
?>