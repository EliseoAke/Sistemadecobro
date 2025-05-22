<?php
class HomeController extends Controller {
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
    
    public function index() {
        // Obtener estadísticas para el dashboard
        $totalClientes = $this->clienteModel->count();
        $totalPlanes = $this->planModel->count();
        $totalPagos = $this->pagoModel->count();
        $totalIngresos = $this->pagoModel->getTotalIngresos();
        
        // Obtener ingresos por mes para el gráfico
        $ingresosPorMes = $this->pagoModel->getIngresosPorMes();
        
        // Obtener distribución de clientes por plan para el gráfico
        $clientesPorPlan = $this->planModel->getClientesPorPlan();
        
        // Obtener clientes con pagos pendientes
        $clientesPendientes = $this->clienteModel->getClientesPendientes(30);
        
        $this->view('home/index', [
            'totalClientes' => $totalClientes,
            'totalPlanes' => $totalPlanes,
            'totalPagos' => $totalPagos,
            'totalIngresos' => $totalIngresos,
            'ingresosPorMes' => $ingresosPorMes,
            'clientesPorPlan' => $clientesPorPlan,
            'clientesPendientes' => $clientesPendientes
        ]);
    }
}
?>