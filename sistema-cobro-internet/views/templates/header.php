<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cobro de Internet</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar bg-primary" id="sidebar-wrapper">
            <div class="sidebar-heading text-white text-center py-4 fs-4 fw-bold border-bottom">
                <i class="fas fa-wifi me-2"></i>NetBilling
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="<?= BASE_URL ?>home" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->controller instanceof HomeController ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="<?= BASE_URL ?>clientes" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->controller instanceof ClientesController ? 'active' : '' ?>">
                    <i class="fas fa-users me-2"></i>Clientes
                </a>
                <a href="<?= BASE_URL ?>planes" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->controller instanceof PlanesController ? 'active' : '' ?>">
                    <i class="fas fa-list me-2"></i>Planes
                </a>
                <a href="<?= BASE_URL ?>pagos" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->controller instanceof PagosController ? 'active' : '' ?>">
                    <i class="fas fa-money-bill-wave me-2"></i>Pagos
                </a>
                <a href="<?= BASE_URL ?>reportes" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->controller instanceof ReportesController ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar me-2"></i>Reportes
                </a>
                <a href="<?= BASE_URL ?>auth/perfil" class="list-group-item list-group-item-action bg-transparent text-white <?= $this->action === 'perfil' ? 'active' : '' ?>">
                    <i class="fas fa-user-cog me-2"></i>Mi Perfil
                </a>
                <a href="<?= BASE_URL ?>auth/logout" class="list-group-item list-group-item-action bg-transparent text-white">
                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="bg-light">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bars primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h4 class="m-0">Sistema de Cobro de Internet</h4>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i><?= $_SESSION['user_nombre'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/perfil"><i class="fas fa-user-cog me-2"></i>Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <div class="container-fluid px-4 py-3">
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['mensaje'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensaje']); ?>
                <?php endif; ?>