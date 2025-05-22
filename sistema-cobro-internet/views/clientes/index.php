<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i> Gestión de Clientes
            </h5>
            <a href="<?= BASE_URL ?>clientes/crear" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nuevo Cliente
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($clientes)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No se encontraron clientes registrados.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Plan</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= $cliente['id'] ?></td>
                                <td><?= $cliente['nombre'] ?></td>
                                <td><?= $cliente['telefono'] ?></td>
                                <td><?= $cliente['direccion'] ?></td>
                                <td>
                                    <?php if (isset($cliente['plan_nombre'])): ?>
                                        <?= $cliente['plan_nombre'] ?>
                                        <span class="badge bg-primary">$<?= number_format($cliente['plan_precio'] ?? 0, 2) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Sin plan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($cliente['activo']) && $cliente['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>pagos/crear?cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/eliminar/<?= $cliente['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este cliente?')">
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