<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago #<?= $pago['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .recibo {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .recibo-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4e73df;
        }
        .recibo-body {
            margin-bottom: 20px;
        }
        .recibo-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .info-col {
            flex: 1;
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .no-print {
            margin-bottom: 20px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .recibo {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print text-center">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Recibo
        </button>
        <a href="<?= BASE_URL ?>pagos" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    
    <div class="recibo">
        <div class="recibo-header">
            <div class="logo">Sistema de Cobro de Internet</div>
            <h4>RECIBO DE PAGO</h4>
            <p>Recibo #<?= str_pad($pago['id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>
        
        <div class="recibo-body">
            <div class="info-row">
                <div class="info-col">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?></p>
                    <p><strong>Cliente:</strong> <?= $cliente['nombre'] ?></p>
                    <p><strong>Teléfono:</strong> <?= $cliente['telefono'] ?></p>
                    <p><strong>Dirección:</strong> <?= $cliente['direccion'] ?></p>
                </div>
                <div class="info-col text-end">
                    <p><strong>Método de Pago:</strong> <?= $pago['metodo_pago'] ?></p>
                    <p><strong>Plan:</strong> <?= $plan['nombre'] ?></p>
                    <p><strong>Velocidad:</strong> <?= $plan['velocidad'] ?></p>
                </div>
            </div>
            
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $pago['descripcion'] ?: 'Pago mensual de servicio de internet' ?></td>
                        <td class="text-end">$<?= number_format($pago['monto'], 2) ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end">Total</th>
                        <th class="text-end">$<?= number_format($pago['monto'], 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="recibo-footer">
            <p>Gracias por su pago.</p>
            <p>Este recibo es un comprobante válido de su pago.</p>
            <p>Para cualquier consulta, comuníquese con nosotros.</p>
        </div>
    </div>
    
    <script>
        // Auto imprimir al cargar
        window.onload = function() {
            // Esperar un momento para que se cargue todo correctamente
            setTimeout(function() {
                // window.print();
            }, 500);
        };
    </script>
</body>
</html>