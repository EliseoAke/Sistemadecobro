<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-plus me-2"></i> Nuevo Plan
            </h5>
            <a href="<?= BASE_URL ?>planes" class="btn btn-secondary">
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
        
        <form action="<?= BASE_URL ?>planes/crear" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Plan *</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= isset($plan['nombre']) ? $plan['nombre'] : '' ?>" required>
                <div class="form-text">Ejemplo: Plan Básico, Plan Premium, etc.</div>
            </div>
            
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= isset($plan['descripcion']) ? $plan['descripcion'] : '' ?></textarea>
                <div class="form-text">Características y detalles del plan.</div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="velocidad" class="form-label">Velocidad *</label>
                    <input type="text" class="form-control" id="velocidad" name="velocidad" value="<?= isset($plan['velocidad']) ? $plan['velocidad'] : '' ?>" required>
                    <div class="form-text">Ejemplo: 10 Mbps, 20 Mbps, etc.</div>
                </div>
                <div class="col-md-6">
                    <label for="precio" class="form-label">Precio Mensual *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="<?= isset($plan['precio']) ? $plan['precio'] : '' ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" <?= (!isset($plan['activo']) || $plan['activo']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activo">
                        Plan Activo
                    </label>
                    <div class="form-text">Los planes inactivos no se mostrarán al registrar nuevos clientes.</div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-eraser me-1"></i> Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>