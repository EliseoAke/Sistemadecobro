<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-chart-bar me-2"></i> Reportes y Estadísticas
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Clientes por Plan</h5>
                        <p class="card-text">Visualiza la distribución de clientes por cada plan de internet.</p>
                        <a href="<?= BASE_URL ?>reportes/clientesPorPlan" class="btn btn-primary">
                            <i class="fas fa-chart-pie me-1"></i> Ver Reporte
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Ingresos por Mes</h5>
                        <p class="card-text">Analiza los ingresos mensuales generados por los pagos de clientes.</p>
                        <a href="<?= BASE_URL ?>reportes/ingresosPorMes" class="btn btn-success">
                            <i class="fas fa-chart-line me-1"></i> Ver Reporte
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Clientes con Pagos Pendientes</h5>
                        <p class="card-text">Identifica los clientes que tienen pagos pendientes o atrasados.</p>
                        <a href="<?= BASE_URL ?>reportes/clientesPendientes" class="btn btn-warning">
                            <i class="fas fa-list-alt me-1"></i> Ver Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Exportar Reportes</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="<?= BASE_URL ?>reportes/exportarClientesPDF" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-pdf text-danger me-2"></i> Reporte de Clientes (PDF)
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="<?= BASE_URL ?>reportes/exportarPagosPDF" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-pdf text-danger me-2"></i> Reporte de Pagos (PDF)
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="<?= BASE_URL ?>reportes/exportarClientesExcel" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-excel text-success me-2"></i> Reporte de Clientes (Excel)
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="<?= BASE_URL ?>reportes/exportarPagosExcel" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-excel text-success me-2"></i> Reporte de Pagos (Excel)
                                </div>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Reportes Personalizados</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>pagos/buscarPorFechas" method="GET">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Generar Reporte de Pagos
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>