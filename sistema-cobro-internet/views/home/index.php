<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Clientes Registrados</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalClientes ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Ingresos Totales</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= number_format($totalIngresos, 2) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Planes Disponibles</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPlanes ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pagos Registrados</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPagos ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ingresos por Mes</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="graficoIngresosMes" data-ingresos='<?= json_encode($ingresosPorMes) ?>' height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Clientes por Plan</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="graficoClientesPlan" data-clientes='<?= json_encode($clientesPorPlan) ?>' height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Clientes con Pagos Pendientes</h6>
                <a href="<?= BASE_URL ?>reportes/clientesPendientes" class="btn btn-sm btn-primary">
                    Ver Todos
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($clientesPendientes)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> No hay clientes con pagos pendientes.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Teléfono</th>
                                    <th>Plan</th>
                                    <th>Último Pago</th>
                                    <th>Días Transcurridos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 0;
                                foreach ($clientesPendientes as $cliente): 
                                    if ($count >= 5) break; // Mostrar solo los primeros 5
                                    $count++;
                                ?>
                                    <tr>
                                        <td><?= $cliente['nombre'] ?></td>
                                        <td><?= $cliente['telefono'] ?></td>
                                        <td>
                                            <?= $cliente['plan_nombre'] ?> 
                                            <span class="badge bg-primary">$<?= number_format($cliente['plan_precio'], 2) ?></span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($cliente['fecha_ultimo_pago'])) ?></td>
                                        <td>
                                            <span class="badge bg-danger"><?= $cliente['dias_transcurridos'] ?> días</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>clientes/ver/<?= $cliente['id'] ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>pagos/crear?cliente_id=<?= $cliente['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-money-bill-wave"></i>
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