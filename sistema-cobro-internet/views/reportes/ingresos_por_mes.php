<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-line me-2"></i> Ingresos por Mes
            </h5>
            <a href="<?= BASE_URL ?>reportes" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="ingresosPorMesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Total Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalGeneral = 0;
                    foreach ($ingresosPorMes as $ingreso): 
                        $totalGeneral += $ingreso['total'];
                    ?>
                        <tr>
                            <td><?= date('F Y', strtotime($ingreso['mes'] . '-01')) ?></td>
                            <td>$<?= number_format($ingreso['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th>Total General</th>
                        <th>$<?= number_format($totalGeneral, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-4">
            <a href="<?= BASE_URL ?>reportes/exportarPagosPDF" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Exportar a PDF
            </a>
            <a href="<?= BASE_URL ?>reportes/exportarPagosExcel" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Exportar a Excel
            </a>
        </div>
    </div>
</div>

<script>
    // Gráfico de Ingresos por Mes
    const ingresosPorMesCtx = document.getElementById('ingresosPorMesChart').getContext('2d');
    const ingresosPorMesChart = new Chart(ingresosPorMesCtx, {
        type: 'bar',
        data: {
            labels: [
                <?php foreach ($ingresosPorMes as $ingreso): ?>
                    '<?= date('M Y', strtotime($ingreso['mes'] . '-01')) ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Ingresos',
                data: [
                    <?php foreach ($ingresosPorMes as $ingreso): ?>
                        <?= $ingreso['total'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Ingresos Mensuales'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
</script>