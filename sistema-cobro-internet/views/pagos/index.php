<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-money-bill-wave me-2"></i> Gestión de Pagos
            </h5>
            <a href="<?= BASE_URL ?>pagos/crear" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Registrar Pago
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-search me-2"></i> Buscar por Fechas
                        </h6>
                        <form action="<?= BASE_URL ?>pagos/buscarPorFechas" method="GET" class="row g-3">
                            <div class="col-md-5">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-5">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-file-export me-2"></i> Exportar Datos
                        </h6>
                        <div class="d-flex gap-2">
                            <a href="<?= BASE_URL ?>reportes/exportarPagosPDF" class="btn btn-danger flex-grow-1">
                                <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
                            </a>
                            <a href="<?= BASE_URL ?>reportes/exportarPagosExcel" class="btn btn-success flex-grow-1">
                                <i class="fas fa-file-excel me-1"></i> Exportar a Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (empty($pagos)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No se encontraron pagos registrados.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td><?= $pago['id'] ?></td>
                                <td><?= $pago['cliente_nombre'] ?></td>
                                <td>$<?= number_format($pago['monto'], 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                <td><?= $pago['metodo_pago'] ?></td>
                                <td><?= $pago['descripcion'] ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>pagos/ver/<?= $pago['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>pagos/generarRecibo/<?= $pago['id'] ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>pagos/eliminar/<?= $pago['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este pago?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>