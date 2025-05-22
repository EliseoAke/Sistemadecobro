<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-cog me-2"></i> Mi Perfil
            </h5>
            <a href="<?= BASE_URL ?>home" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
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
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
                            </div>
                        </div>
                        <h5 class="card-title"><?= $usuario['nombre'] ?></h5>
                        <p class="text-muted"><?= $usuario['email'] ?></p>
                        <p>
                            <span class="badge bg-<?= $usuario['rol'] == 'administrador' ? 'primary' : 'secondary' ?>">
                                <?= ucfirst($usuario['rol']) ?>
                            </span>
                        </p>
                        <p class="text-muted small">
                            <i class="fas fa-calendar me-1"></i> Miembro desde: <?= date('d/m/Y', strtotime($usuario['fecha_creacion'])) ?>
                        </p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i> Seguridad
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Se recomienda cambiar la contraseña periódicamente para mantener la seguridad de tu cuenta.
                        </p>
                        <p class="text-muted small">
                            <i class="fas fa-lock me-1"></i> Última actualización de contraseña: 
                            <?= isset($usuario['fecha_actualizacion']) ? date('d/m/Y', strtotime($usuario['fecha_actualizacion'])) : 'Nunca' ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Editar Información
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>auth/perfil" method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['nombre'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $usuario['email'] ?>" required>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h6 class="mb-3">Cambiar Contraseña</h6>
                            <div class="mb-3">
                                <label for="password_actual" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" id="password_actual" name="password_actual">
                                <div class="form-text">Deja este campo en blanco si no deseas cambiar tu contraseña.</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password_nueva" name="password_nueva">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar">
                                </div>
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
            </div>
        </div>
    </div>
</div>