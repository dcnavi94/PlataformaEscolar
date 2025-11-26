<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-graph-up-arrow"></i> Dashboard Ejecutivo</h2>
            <p class="text-muted">Vista consolidada de indicadores clave de desempeño</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <!-- Alumnos Activos -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Alumnos Activos</h6>
                            <h2 class="text-primary mb-1"><?= number_format($alumnosStats['activos']) ?></h2>
                            <small class="text-muted">de <?= number_format($alumnosStats['total']) ?> total</small>
                        </div>
                        <div>
                            <i class="bi bi-people-fill text-primary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos del Mes -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Ingresos (Mes)</h6>
                            <h2 class="text-success mb-1">$<?= number_format($financialKPIs['ingresos'], 0) ?></h2>
                            <small class="text-muted">Total recaudado</small>
                        </div>
                        <div>
                            <i class="bi bi-cash-stack text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Morosidad -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Morosidad</h6>
                            <h2 class="text-danger mb-1">$<?= number_format($financialKPIs['morosidad'], 0) ?></h2>
                            <small class="badge bg-danger"><?= $financialKPIs['morosidad_porcentaje'] ?>%</small>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promedio General -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Promedio General</h6>
                            <h2 class="text-info mb-1"><?= $academicKPIs['promedio_general'] ?></h2>
                            <small class="text-muted">Institucional</small>
                        </div>
                        <div>
                            <i class="bi bi-bar-chart-fill text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencia -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Asistencia</h6>
                            <h2 class="text-warning mb-1"><?= $academicKPIs['asistencia_promedio'] ?>%</h2>
                            <small class="text-muted">Promedio</small>
                        </div>
                        <div>
                            <i class="bi bi-check-circle-fill text-warning" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasa de Deserción -->
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card kpi-card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small">Deserción (12m)</h6>
                            <h2 class="text-secondary mb-1"><?= $desercion['tasa_desercion'] ?>%</h2>
                            <small class="text-muted"><?= $desercion['total_bajas'] ?> bajas</small>
                        </div>
                        <div>
                            <i class="bi bi-arrow-down-circle text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Ingresos Mensuales -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Ingresos Mensuales (Últimos 6 Meses)</h5>
                </div>
                <div class="card-body">
                    <canvas id="ingresosChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Programa -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución por Programa</h5>
                </div>
                <div class="card-body">
                    <canvas id="programasChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Estado de Pagos -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Estado de Pagos (Por Monto)</h5>
                </div>
                <div class="card-body">
                    <canvas id="estadoPagosChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats Table -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Resumen de Alumnos</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><i class="bi bi-circle-fill text-success"></i> Activos</td>
                                <td class="text-end"><strong><?= number_format($alumnosStats['activos']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-circle-fill text-secondary"></i> Inactivos</td>
                                <td class="text-end"><strong><?= number_format($alumnosStats['inactivos']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-circle-fill text-danger"></i> Bajas</td>
                                <td class="text-end"><strong><?= number_format($alumnosStats['bajas']) ?></strong></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-circle-fill text-primary"></i> Egresados</td>
                                <td class="text-end"><strong><?= number_format($alumnosStats['egresados']) ?></strong></td>
                            </tr>
                            <tr class="table-active">
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong><?= number_format($alumnosStats['total']) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Vistas Detalladas</h6>
                    <a href="<?= BASE_URL ?>/dashboardejecutivo/financiero" class="btn btn-outline-success m-2">
                        <i class="bi bi-cash"></i> Dashboard Financiero
                    </a>
                    <a href="<?= BASE_URL ?>/dashboardejecutivo/academico" class="btn btn-outline-info m-2">
                        <i class="bi bi-book"></i> Dashboard Académico
                    </a>
                    <a href="<?= BASE_URL ?>/dashboardejecutivo/estudiantes" class="btn btn-outline-primary m-2">
                        <i class="bi bi-people"></i> Dashboard Estudiantes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Ingresos Chart
const ingresosData = <?= $ingresosChart ?>;
const ctxIngresos = document.getElementById('ingresosChart').getContext('2d');
new Chart(ctxIngresos, {
    type: 'line',
    data: ingresosData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Programas Chart
const programasData = <?= $programasChart ?>;
const ctxProgramas = document.getElementById('programasChart').getContext('2d');
new Chart(ctxProgramas, {
    type: 'doughnut',
    data: programasData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Estado Pagos Chart
const estadoPagosData = <?= $estadoPagosChart ?>;
const ctxEstadoPagos = document.getElementById('estadoPagosChart').getContext('2d');
new Chart(ctxEstadoPagos, {
    type: 'bar',
    data: estadoPagosData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<style>
.kpi-card {
    border-left: 4px solid #007bff;
    transition: transform 0.2s;
}
.kpi-card:hover {
    transform: translateY(-5px);
}
</style>

<?php require_once '../app/Views/layouts/footer.php'; ?>
