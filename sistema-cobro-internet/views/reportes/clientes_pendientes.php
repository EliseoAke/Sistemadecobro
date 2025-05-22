<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i> Clientes con Pagos Pendientes
            </h5>
            <a href="<?= BASE_URL ?>reportes" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($clientesPendientes)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> No hay clientes con pagos pendientes.
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i> Se muestran los clientes que no han realizado pagos en los últimos 30 días.
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Plan</th>
                            <th>Último Pago</th>
                            <th>Días Transcurridos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientesPendientes as $cliente): ?>
                            <tr>
                                <td><?= $cliente['id'] ?></td>
                                <td><?= $cliente['nombre'] ?></td>
                                <td><?= $cliente['telefono'] ?></td>
                                <td>
                                    <?= $cliente['plan_nombre'] ?> 
                                    <span class="badge bg-primary">$<?= number_format($cliente['plan_precio'], 2) ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($cliente['fecha_ultimo_pago'])) ?></td>
                                <td>
                                    <span class="badge bg-danger"><?= $cliente['dias_transcurridos'] ?> días</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>pagos/crear?cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="<?= BASE_URL ?>reportes/exportarClientesPendientesPDF" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
                </a>
                <a href="<?= BASE_URL ?>reportes/exportarClientesPendientesExcel" class="btn btn-success ms-2">
                    <i class="fas fa-file-excel me-1"></i> Exportar a Excel
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>