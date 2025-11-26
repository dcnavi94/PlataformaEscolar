<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-people"></i> Dashboard de Estudiantes</h2>
            <p class="text-muted">Vista detallada de indicadores de estudiantes</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <h3 class="mt-2"><?= number_format($alumnosStats['activos']) ?></h3>
                    <p class="text-muted mb-0">Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-secondary">
                <div class="card-body text-center">
                    <i class="bi bi-pause-circle text-secondary" style="font-size: 3rem;"></i>
                    <h3 class="mt-2"><?= number_format($alumnosStats['inactivos']) ?></h3>
                    <p class="text-muted mb-0">Inactivos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                    <h3 class="mt-2"><?= number_format($alumnosStats['bajas']) ?></h3>
                    <p class="text-muted mb-0">Bajas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-mortarboard text-primary" style="font-size: 3rem;"></i>
                    <h3 class="mt-2"><?= number_format($alumnosStats['egresados']) ?></h3>
                    <p class="text-muted mb-0">Egresados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deserción -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-graph-down"></i> Tasa de Deserción (Últimos 12 meses)</h5>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="display-4 text-danger"><?= $desercion['tasa_desercion'] ?>%</h1>
                            <p class="text-muted">Total de bajas: <?= $desercion['total_bajas'] ?> estudiantes</p>
                        </div>
                        <div class="col-md-6">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-danger" style="width: <?= $desercion['tasa_desercion'] ?>%">
                                    <?= $desercion['tasa_desercion'] ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Nuevos Ingresos por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="nuevosIngresosChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <a href="<?= BASE_URL ?>/dashboardejecutivo" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard Principal
            </a>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const data = <?= $nuevosIngresosChart ?>;
const ctx = document.getElementById('nuevosIngresosChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php require_once '../app/Views/layouts/footer.php'; ?>
