<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-list me-2"></i> Planes de Internet
    </h1>
    <a href="<?= BASE_URL ?>planes/crear" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nuevo Plan
    </a>
</div>

<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (empty($planes)): ?>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i> No hay planes registrados. Crea un nuevo plan para comenzar.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Velocidad</th>
                            <th>Precio</th>
                            <th>Clientes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planes as $plan): ?>
                            <tr>
                                <td><?= $plan['nombre'] ?></td>
                                <td><?= $plan['velocidad'] ?></td>
                                <td>$<?= number_format($plan['precio'], 2) ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= $plan['total_clientes'] ?> clientes</span>
                                    <?php if ($plan['clientes_activos'] > 0): ?>
                                        <span class="badge bg-success"><?= $plan['clientes_activos'] ?> activos</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($plan['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>planes/editar/<?= $plan['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($plan['total_clientes'] == 0): ?>
                                            <a href="<?= BASE_URL ?>planes/eliminar/<?= $plan['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este plan?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-danger" disabled title="No se puede eliminar porque hay clientes que utilizan este plan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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