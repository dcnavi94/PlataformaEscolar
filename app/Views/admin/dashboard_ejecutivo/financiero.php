<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-cash"></i> Dashboard Financiero</h2>
            <p class="text-muted">Vista detallada de indicadores financieros</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Ingresos del Año</h6>
                    <h2 class="text-success">$<?= number_format($financialKPIs['ingresos'], 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Cuentas por Cobrar</h6>
                    <h2 class="text-warning">$<?= number_format($financialKPIs['cuentas_por_cobrar'], 0) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Morosidad</h6>
                    <h2 class="text-danger">$<?= number_format($financialKPIs['morosidad'], 0) ?></h2>
                    <span class="badge bg-danger"><?= $financialKPIs['morosidad_porcentaje'] ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ingresos Mensuales (Último Año)</h5>
                </div>
                <div class="card-body">
                    <canvas id="ingresosChart" height="80"></canvas>
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
const data = <?= $ingresosChart ?>;
const ctx = document.getElementById('ingresosChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
