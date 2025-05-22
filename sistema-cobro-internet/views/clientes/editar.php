<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-edit me-2"></i> Editar Cliente
            </h5>
            <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary">
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
        
        <form action="<?= BASE_URL ?>clientes/editar/<?= $cliente['id'] ?>" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre Completo *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $cliente['nombre'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono *</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $cliente['telefono'] ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección *</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?= $cliente['direccion'] ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $cliente['email'] ?>">
                    <div class="form-text">Opcional, para envío de notificaciones.</div>
                </div>
                <div class="col-md-6">
                    <label for="plan_id" class="form-label">Plan de Internet *</label>
                    <select class="form-select" id="plan_id" name="plan_id" required>
                        <option value="">Seleccione un plan</option>
                        <?php foreach ($planes as $plan): ?>
                            <option value="<?= $plan['id'] ?>" <?= ($cliente['plan_id'] == $plan['id']) ? 'selected' : '' ?>>
                                <?= $plan['nombre'] ?> - $<?= number_format($plan['precio'], 2) ?> - <?= $plan['velocidad'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_instalacion" class="form-label">Fecha de Instalación *</label>
                    <input type="date" class="form-control" id="fecha_instalacion" name="fecha_instalacion" value="<?= $cliente['fecha_instalacion'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">Estado</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" <?= (isset($cliente['activo']) && $cliente['activo']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notas" class="form-label">Notas</label>
                <textarea class="form-control" id="notas" name="notas" rows="3"><?= $cliente['notas'] ?></textarea>
                <div class="form-text">Información adicional sobre el cliente o la instalación.</div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo me-1"></i> Restablecer
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>