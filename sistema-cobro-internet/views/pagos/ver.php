<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-invoice me-2"></i> Detalle de Pago #<?= $pago['id'] ?>
            </h5>
            <div>
                <a href="<?= BASE_URL ?>pagos/generarRecibo/<?= $pago['id'] ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-print me-1"></i> Imprimir Recibo
                </a>
                <a href="<?= BASE_URL ?>pagos" class="btn btn-secondary ms-2">
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
                            <i class="fas fa-user me-2"></i> Información del Cliente
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> <?= $cliente['nombre'] ?></p>
                        <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
                        <p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>
                        <p><strong>Email:</strong> <?= $cliente['email'] ?: 'No registrado' ?></p>
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
                        <p><strong>Plan:</strong> <?= $plan['nombre'] ?></p>
                        <p><strong>Velocidad:</strong> <?= $plan['velocidad'] ?></p>
                        <p><strong>Precio Mensual:</strong> $<?= number_format($plan['precio'], 2) ?></p>
                        <p><strong>Descripción:</strong> <?= $plan['descripcion'] ?: 'Sin descripción' ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i> Detalles del Pago
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Monto:</strong> $<?= number_format($pago['monto'], 2) ?></p>
                        <p><strong>Método de Pago:</strong> <?= $pago['metodo_pago'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Fecha de Pago:</strong> <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></p>
                        <p><strong>Descripción:</strong> <?= $pago['descripcion'] ?: 'Sin descripción' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>