<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Cobro de Internet</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo-icon {
            font-size: 2.5rem;
            color: #4e73df;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <i class="fas fa-wifi logo-icon"></i>
            <h2 class="text-primary">NetBilling</h2>
        </div>
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4>Sistema de Cobro de Internet</h4>
            </div>
            <div class="card-body p-4">
                <h5 class="card-title text-center mb-4">Iniciar Sesión</h5>
                
                <?php if (isset($errores) && !empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>auth/login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" placeholder="admin@sistema.com" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••" required>
                        </div>
                        <div class="form-text text-muted mt-1">
                            <i class="fas fa-info-circle"></i> Credenciales por defecto: admin@sistema.com / admin123
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small text-muted">
                    Sistema de Cobro de Internet &copy; <?= date('Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>