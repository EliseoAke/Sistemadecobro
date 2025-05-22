// Inicializar DataTables
$(document).ready(function() {
    // Verificar si hay tablas con la clase dataTable
    if ($('table.dataTable').length > 0) {
        $('table.dataTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            responsive: true
        });
    }
    
    // Inicializar DataTables para tablas con id dataTable
    if ($('#dataTable').length > 0 && !$.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            responsive: true
        });
    }
    
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
    
    // Toggle del sidebar
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    
    // Inicializar gráficos si existen los canvas
    inicializarGraficos();
});

// Función para inicializar los gráficos
function inicializarGraficos() {
    // Gráfico de ingresos por mes
    if (document.getElementById('graficoIngresosMes')) {
        const ctx = document.getElementById('graficoIngresosMes').getContext('2d');
        const ingresosPorMes = JSON.parse(document.getElementById('graficoIngresosMes').dataset.ingresos || '[]');
        
        if (ingresosPorMes.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ingresosPorMes.map(item => item.mes_nombre),
                    datasets: [{
                        label: 'Ingresos por Mes',
                        data: ingresosPorMes.map(item => item.total),
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$ ' + context.raw.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$ ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    
    // Gráfico de clientes por plan
    if (document.getElementById('graficoClientesPlan')) {
        const ctx = document.getElementById('graficoClientesPlan').getContext('2d');
        const clientesPorPlan = JSON.parse(document.getElementById('graficoClientesPlan').dataset.clientes || '[]');
        
        if (clientesPorPlan.length > 0) {
            // Generar colores aleatorios para cada plan
            const colores = generarColores(clientesPorPlan.length);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: clientesPorPlan.map(item => item.nombre),
                    datasets: [{
                        data: clientesPorPlan.map(item => item.total_clientes),
                        backgroundColor: colores,
                        hoverBackgroundColor: colores.map(color => color.replace('0.7', '0.9')),
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    return label + ': ' + value + ' clientes';
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    }
}

// Función para generar colores aleatorios
function generarColores(cantidad) {
    const colores = [
        'rgba(78, 115, 223, 0.7)',
        'rgba(28, 200, 138, 0.7)',
        'rgba(54, 185, 204, 0.7)',
        'rgba(246, 194, 62, 0.7)',
        'rgba(231, 74, 59, 0.7)',
        'rgba(104, 109, 224, 0.7)',
        'rgba(58, 83, 155, 0.7)',
        'rgba(113, 128, 150, 0.7)',
        'rgba(47, 53, 66, 0.7)',
        'rgba(0, 184, 148, 0.7)'
    ];
    
    // Si necesitamos más colores de los predefinidos, generamos aleatorios
    if (cantidad > colores.length) {
        for (let i = colores.length; i < cantidad; i++) {
            const r = Math.floor(Math.random() * 255);
            const g = Math.floor(Math.random() * 255);
            const b = Math.floor(Math.random() * 255);
            colores.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
        }
    }
    
    return colores.slice(0, cantidad);
}