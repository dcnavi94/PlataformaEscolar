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
