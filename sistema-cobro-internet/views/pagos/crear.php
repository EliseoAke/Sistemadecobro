<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-money-bill-wave me-2"></i> Registrar Pago
            </h5>
            <a href="<?= BASE_URL ?>pagos" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($errores) && !empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>pagos/crear" method="POST">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="cliente_id" class="form-label">Cliente *</label>
                    <select class="form-select" id="cliente_id" name="cliente_id" required <?= isset($cliente_id) ? 'disabled' : '' ?>>
                        <option value="">Seleccione un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= (isset($cliente_id) && $cliente_id == $cliente['id']) ? 'selected' : '' ?>>
                                <?= $cliente['nombre'] ?> - <?= $cliente['telefono'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($cliente_id)): ?>
                        <input type="hidden" name="cliente_id" value="<?= $cliente_id ?>">
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="plan_info" class="form-label">Plan</label>
                    <input type="text" class="form-control" id="plan_info" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="monto" class="form-label">Monto *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control" id="monto" name="monto" value="<?= isset($pago['monto']) ? $pago['monto'] : '' ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="metodo_pago" class="form-label">Método de Pago *</label>
                    <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                        <option value="">Seleccione un método</option>
                        <option value="Efectivo" <?= (isset($pago['metodo_pago']) && $pago['metodo_pago'] == 'Efectivo') ? 'selected' : '' ?>>Efectivo</option>
                        <option value="Transferencia" <?= (isset($pago['metodo_pago']) && $pago['metodo_pago'] == 'Transferencia') ? 'selected' : '' ?>>Transferencia</option>
                        <option value="Depósito" <?= (isset($pago['metodo_pago']) && $pago['metodo_pago'] == 'Depósito') ? 'selected' : '' ?>>Depósito</option>
                        <option value="Tarjeta" <?= (isset($pago['metodo_pago']) && $pago['metodo_pago'] == 'Tarjeta') ? 'selected' : '' ?>>Tarjeta</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                    <input type="datetime-local" class="form-control" id="fecha_pago" name="fecha_pago" value="<?= isset($pago['fecha_pago']) ? $pago['fecha_pago'] : date('Y-m-d\TH:i') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= isset($pago['descripcion']) ? $pago['descripcion'] : 'Pago mensual de servicio de internet' ?>">
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-eraser me-1"></i> Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Registrar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('cliente_id');
    const planInfo = document.getElementById('plan_info');
    const montoInput = document.getElementById('monto');
    
    // Función para cargar la información del plan
    function cargarPlan(clienteId) {
        if (!clienteId) {
            planInfo.value = '';
            montoInput.value = '';
            return;
        }
        
        // Hacer una petición AJAX para obtener la información del plan
        fetch('<?= BASE_URL ?>clientes/getPlanInfo/' + clienteId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    planInfo.value = `${data.plan.nombre} - ${data.plan.velocidad} - $${data.plan.precio}`;
                    montoInput.value = data.plan.precio;
                } else {
                    planInfo.value = 'Cliente sin plan asignado';
                    montoInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                planInfo.value = 'Error al cargar la información';
            });
    }
    
    // Cargar la información del plan al cambiar el cliente
    clienteSelect.addEventListener('change', function() {
        cargarPlan(this.value);
    });
    
    // Cargar la información del plan al cargar la página si hay un cliente seleccionado
    if (clienteSelect.value) {
        cargarPlan(clienteSelect.value);
    }
});
</script>