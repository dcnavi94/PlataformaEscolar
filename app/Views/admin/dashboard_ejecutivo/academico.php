<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-book"></i> Dashboard Académico</h2>
            <p class="text-muted">Vista detallada de indicadores académicos</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Promedio General Institucional</h6>
                    <h1 class="text-info display-3"><?= $academicKPIs['promedio_general'] ?></h1>
                    <p class="text-muted">Escala de 0 a 10</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Asistencia Promedio</h6>
                    <h1 class="text-success display-3"><?= $academicKPIs['asistencia_promedio'] ?>%</h1>
                    <p class="text-muted">Del mes actual</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> Nota</h5>
                <p class="mb-0">
                    Los datos académicos detallados requieren configuración adicional en el sistema.
                    Actualmente se muestran valores por defecto.
                </p>
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
