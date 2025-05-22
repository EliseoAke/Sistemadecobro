<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user me-2"></i> Detalles del Cliente
            </h5>
            <div>
                <a href="<?= BASE_URL ?>pagos/crear?cliente_id=<?= $cliente['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-money-bill-wave me-1"></i> Registrar Pago
                </a>
                <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i> Información Personal
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> <?= $cliente['nombre'] ?></p>
                        <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
                        <p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>
                        <p><strong>Email:</strong> <?= $cliente['email'] ?: 'No registrado' ?></p>
                        <p><strong>Fecha de Instalación:</strong> <?= date('d/m/Y', strtotime($cliente['fecha_instalacion'])) ?></p>
                        <p>
                            <strong>Estado:</strong> 
                            <?php if (isset($cliente['activo']) && $cliente['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($cliente['notas'])): ?>
                            <p><strong>Notas:</strong> <?= $cliente['notas'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i> Información del Plan
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (isset($plan) && $plan): ?>
                            <p><strong>Plan:</strong> <?= $plan['nombre'] ?></p>
                            <p><strong>Velocidad:</strong> <?= $plan['velocidad'] ?></p>
                            <p><strong>Precio Mensual:</strong> $<?= number_format($plan['precio'], 2) ?></p>
                            <p><strong>Descripción:</strong> <?= $plan['descripcion'] ?: 'Sin descripción' ?></p>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Este cliente no tiene un plan asignado.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i> Historial de Pagos
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($pagos)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No hay pagos registrados para este cliente.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos as $pago): ?>
                                    <tr>
                                        <td><?= $pago['id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></td>
                                        <td>$<?= number_format($pago['monto'], 2) ?></td>
                                        <td><?= $pago['metodo_pago'] ?></td>
                                        <td><?= $pago['descripcion'] ?: 'Pago mensual' ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>pagos/ver/<?= $pago['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>pagos/generarRecibo/<?= $pago['id'] ?>" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fas fa-file-invoice"></i>
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
    </div>
</div>