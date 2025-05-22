<?php
class ReportesController extends Controller {
    private $clienteModel;
    private $pagoModel;
    private $planModel;
    
    public function __construct() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        $this->clienteModel = $this->model('Cliente');
        $this->pagoModel = $this->model('Pago');
        $this->planModel = $this->model('Plan');
    }
    
    public function index() {
        $this->view('reportes/index');
    }
    
    public function clientesPorPlan() {
        $clientesPorPlan = $this->clienteModel->getEstadisticasPorPlan();
        $this->view('reportes/clientes_por_plan', ['clientesPorPlan' => $clientesPorPlan]);
    }
    
    public function ingresosPorMes() {
        $ingresosPorMes = $this->pagoModel->getEstadisticasPorMes();
        $this->view('reportes/ingresos_por_mes', ['ingresosPorMes' => $ingresosPorMes]);
    }
    
    public function clientesPendientes() {
        $clientesPendientes = $this->clienteModel->getClientesConPagosPendientes();
        $this->view('reportes/clientes_pendientes', ['clientesPendientes' => $clientesPendientes]);
    }
    
    public function exportarClientesPDF() {
        // Aquí se implementaría la generación del PDF con una librería como FPDF o TCPDF
        $clientes = $this->clienteModel->getClientesConPlanes();
        
        // Ejemplo básico con FPDF (requiere incluir la librería)
        require_once 'libs/fpdf/fpdf.php';
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Reporte de Clientes');
        $pdf->Ln(15);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Nombre');
        $pdf->Cell(60, 10, 'Dirección');
        $pdf->Cell(30, 10, 'Teléfono');
        $pdf->Cell(30, 10, 'Plan');
        $pdf->Cell(30, 10, 'Precio');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', '', 12);
        foreach ($clientes as $cliente) {
            $pdf->Cell(40, 10, $cliente['nombre']);
            $pdf->Cell(60, 10, $cliente['direccion']);
            $pdf->Cell(30, 10, $cliente['telefono']);
            $pdf->Cell(30, 10, $cliente['plan_nombre']);
            $pdf->Cell(30, 10, '$' . number_format($cliente['plan_precio'], 2));
            $pdf->Ln(10);
        }
        
        $pdf->Output('D', 'reporte_clientes.pdf');
    }
    
    public function exportarPagosPDF() {
        // Aquí se implementaría la generación del PDF con una librería como FPDF o TCPDF
        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fecha_inicio = $_GET['fecha_inicio'];
            $fecha_fin = $_GET['fecha_fin'];
            
            $pagos = $this->pagoModel->getPagosPorFechas($fecha_inicio, $fecha_fin);
            
            require_once 'libs/fpdf/fpdf.php';
            
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'Reporte de Pagos');
            $pdf->Ln(15);
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(40, 10, 'Cliente');
            $pdf->Cell(30, 10, 'Fecha');
            $pdf->Cell(30, 10, 'Monto');
            $pdf->Cell(30, 10, 'Método');
            $pdf->Cell(60, 10, 'Descripción');
            $pdf->Ln(10);
            
            $pdf->SetFont('Arial', '', 12);
            foreach ($pagos as $pago) {
                $pdf->Cell(40, 10, $pago['cliente_nombre']);
                $pdf->Cell(30, 10, date('d/m/Y', strtotime($pago['fecha_pago'])));
                $pdf->Cell(30, 10, '$' . number_format($pago['monto'], 2));
                $pdf->Cell(30, 10, $pago['metodo_pago']);
                $pdf->Cell(60, 10, $pago['descripcion']);
                $pdf->Ln(10);
            }
            
            $pdf->Output('D', 'reporte_pagos.pdf');
        } else {
            $this->view('reportes/exportar_pagos');
        }
    }
    
    public function exportarClientesExcel() {
        // Aquí se implementaría la generación del Excel con una librería como PhpSpreadsheet
        $clientes = $this->clienteModel->getClientesConPlanes();
        
        // Ejemplo básico con PhpSpreadsheet (requiere incluir la librería)
        require_once 'libs/phpspreadsheet/vendor/autoload.php';
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Nombre');
        $sheet->setCellValue('B1', 'Dirección');
        $sheet->setCellValue('C1', 'Teléfono');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Plan');
        $sheet->setCellValue('F1', 'Precio');
        $sheet->setCellValue('G1', 'Fecha Último Pago');
        
        $row = 2;
        foreach ($clientes as $cliente) {
            $sheet->setCellValue('A' . $row, $cliente['nombre']);
            $sheet->setCellValue('B' . $row, $cliente['direccion']);
            $sheet->setCellValue('C' . $row, $cliente['telefono']);
            $sheet->setCellValue('D' . $row, $cliente['email']);
            $sheet->setCellValue('E' . $row, $cliente['plan_nombre']);
            $sheet->setCellValue('F' . $row, $cliente['plan_precio']);
            $sheet->setCellValue('G' . $row, $cliente['fecha_ultimo_pago']);
            $row++;
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_clientes.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }
}
?>