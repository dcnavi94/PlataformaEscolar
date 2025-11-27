<div class="row">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-3"><?= $total_alumnos ?></h3>
                <p class="text-muted">Total Alumnos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-person-check-fill text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-3"><?= $alumnos_inscritos ?></h3>
                <p class="text-muted">Inscritos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-3"><?= $cargos_pendientes ?></h3>
                <p class="text-muted">Cargos Pendientes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-cash-stack text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-3">$<?= number_format($ingresos_mes, 2) ?></h3>
                <p class="text-muted">Ingresos del Mes</p>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW 1 -->
<div class="row mt-4">
    <!-- Ingresos Mensuales -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-graph-up me-2"></i> Ingresos Últimos 6 Meses
            </div>
            <div class="card-body">
                <canvas id="ingresosChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Distribución de Pagos -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-pie-chart me-2"></i> Métodos de Pago
            </div>
            <div class="card-body">
                <canvas id="distribucionChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW 2 -->
<div class="row mt-4">
    <!-- Morosidad por Programa -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <i class="bi bi-exclamation-octagon me-2"></i> Morosidad por Programa
            </div>
            <div class="card-body">
                <canvas id="morosidadChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Alumnos por Estatus -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-people me-2"></i> Alumnos por Estatus
            </div>
            <div class="card-body">
                <canvas id="estatusChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Transacciones -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-clock-history me-2"></i> Últimas Transacciones
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Alumno</th>
                                <th>Conceptos</th>
                                <th>Método</th>
                                <th class="text-end">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($chartData['ultimas_transacciones'])): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay transacciones recientes</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($chartData['ultimas_transacciones'] as $trans): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($trans['fecha_pago'])) ?></td>
                                    <td><?= htmlspecialchars($trans['alumno_nombre']) ?></td>
                                    <td><small><?= htmlspecialchars($trans['conceptos'] ?? 'N/A') ?></small></td>
                                    <td>
                                        <span class="badge bg-<?= $trans['metodo_pago'] == 'EFECTIVO' ? 'success' : ($trans['metodo_pago'] == 'PAYPAL' ? 'primary' : 'info') ?>">
                                            <?= htmlspecialchars($trans['metodo_pago']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">$<?= number_format($trans['monto_total'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="row mt-4">
    <div class="col-md-12">
        <h4>Accesos Rápidos</h4>
        <div class="list-group">
            <a href="<?= BASE_URL ?>/alumnoadmin/create" class="list-group-item list-group-item-action">
                <i class="bi bi-plus-circle"></i> Registrar Nuevo Alumno
            </a>
            <a href="<?= BASE_URL ?>/reporte/index" class="list-group-item list-group-item-action">
                <i class="bi bi-graph-up"></i> Ver Reportes
            </a>
            <a href="<?= BASE_URL ?>/pago/historial" class="list-group-item list-group-item-action">
                <i class="bi bi-credit-card"></i> Ver Historial de Pagos
            </a>
            <a href="<?= BASE_URL ?>/grupo/index" class="list-group-item list-group-item-action">
                <i class="bi bi-people"></i> Gestionar Grupos
            </a>
        </div>
    </div>
</div>

<script>
// Chart.js Configuration
const chartColors = {
    navy: '#003366',
    orange: '#FF6600',
    lightOrange: '#FF8C42',
    success: '#28a745',
    danger: '#dc3545',
    info: '#17a2b8',
    gray: '#6c757d'
};

// 1. INGRESOS MENSUALES (Line Chart)
const ingresosData = <?= json_encode($chartData['ingresos_mensuales']) ?>;
const ingresosLabels = ingresosData.map(item => {
    const [year, month] = item.mes.split('-');
    const months = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    return months[parseInt(month) - 1] + ' ' + year;
});
const ingresosValues = ingresosData.map(item => parseFloat(item.total));

new Chart(document.getElementById('ingresosChart'), {
    type: 'line',
    data: {
        labels: ingresosLabels,
        datasets: [{
            label: 'Ingresos ($)',
            data: ingresosValues,
            borderColor: chartColors.navy,
            backgroundColor: 'rgba(0, 51, 102, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return '$' + context.parsed.y.toLocaleString('es-MX', {minimumFractionDigits: 2});
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString('es-MX');
                    }
                }
            }
        }
    }
});

// 2. DISTRIBUCIÓN DE PAGOS (Pie Chart)
const distribucionData = <?= json_encode($chartData['distribucion_pagos']) ?>;
const distribucionLabels = distribucionData.map(item => item.metodo_pago);
const distribucionValues = distribucionData.map(item => parseFloat(item.total));
const distribucionColors = distribucionLabels.map(label => {
    if (label === 'EFECTIVO') return chartColors.success;
    if (label === 'PAYPAL') return chartColors.navy;
    if (label === 'TRANSFERENCIA') return chartColors.info;
    return chartColors.gray;
});

new Chart(document.getElementById('distribucionChart'), {
    type: 'doughnut',
    data: {
        labels: distribucionLabels,
        datasets: [{
            data: distribucionValues,
            backgroundColor: distribucionColors,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return label + ': $' + value.toLocaleString('es-MX', {minimumFractionDigits: 2}) + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// 3. MOROSIDAD POR PROGRAMA (Horizontal Bar Chart)
const morosidadData = <?= json_encode($chartData['morosos_por_programa']) ?>;
const morosidadLabels = morosidadData.map(item => item.programa);
const morosidadValues = morosidadData.map(item => parseInt(item.total_morosos));

new Chart(document.getElementById('morosidadChart'), {
    type: 'bar',
    data: {
        labels: morosidadLabels.length > 0 ? morosidadLabels : ['Sin datos'],
        datasets: [{
            label: 'Alumnos Morosos',
            data: morosidadValues.length > 0 ? morosidadValues : [0],
            backgroundColor: chartColors.orange,
            borderColor: chartColors.orange,
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // Horizontal bars
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 4. ALUMNOS POR ESTATUS (Doughnut Chart)
const estatusData = <?= json_encode($chartData['alumnos_por_estatus']) ?>;
const estatusLabels = estatusData.map(item => item.estatus);
const estatusValues = estatusData.map(item => parseInt(item.cantidad));
const estatusColors = estatusLabels.map(label => {
    if (label === 'INSCRITO') return chartColors.success;
    if (label === 'BAJA') return chartColors.danger;
    if (label === 'EGRESADO') return chartColors.gray;
    return chartColors.info;
});

new Chart(document.getElementById('estatusChart'), {
    type: 'doughnut',
    data: {
        labels: estatusLabels,
        datasets: [{
            data: estatusValues,
            backgroundColor: estatusColors,
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});
</script>
