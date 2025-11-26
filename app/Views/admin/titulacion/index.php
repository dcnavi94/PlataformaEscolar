<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-mortarboard-fill"></i> Sistema de Titulación</h2>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <h6 class="text-muted">Total Procesos</h6>
                    <h2 class="text-primary"><?= $stats['total'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <h6 class="text-muted">En Revisión</h6>
                    <h2 class="text-warning"><?= $stats['en_revision'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <h6 class="text-muted">Aprobados</h6>
                    <h2 class="text-info"><?= $stats['aprobados'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <h6 class="text-muted">Titulados</h6>
                    <h2 class="text-success"><?= $stats['titulados'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-list-check text-primary" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Requisitos</h5>
                    <p class="text-muted">Configurar requisitos por programa</p>
                    <a href="<?= BASE_URL ?>/titulacionadmin/requisitos" class="btn btn-primary">Gestionar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-folder-check text-info" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Procesos</h5>
                    <p class="text-muted">Revisar solicitudes de titulación</p>
                    <a href="<?= BASE_URL ?>/titulacionadmin/procesos" class="btn btn-info">Ver Procesos</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Egresados</h5>
                    <p class="text-muted">Registro de egresados</p>
                    <a href="<?= BASE_URL ?>/titulacionadmin/egresados" class="btn btn-success">Ver Registro</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Processes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Procesos Recientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Alumno</th>
                                    <th>Programa</th>
                                    <th>Modalidad</th>
                                    <th>Estado</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($procesos)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay procesos registrados</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($procesos as $proceso): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($proceso['numero_folio']) ?></td>
                                            <td><?= htmlspecialchars($proceso['alumno_nombre'] . ' ' . $proceso['alumno_apellidos']) ?></td>
                                            <td><?= htmlspecialchars($proceso['programa_nombre']) ?></td>
                                            <td><?= $proceso['modalidad'] ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = [
                                                    'SOLICITADO' => 'secondary',
                                                    'EN_REVISION' => 'warning',
                                                    'APROBADO' => 'info',
                                                    'TITULADO' => 'success',
                                                    'RECHAZADO' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $badgeClass[$proceso['estado']] ?? 'secondary' ?>">
                                                    <?= $proceso['estado'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($proceso['fecha_solicitud'])) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/titulacionadmin/revisar/<?= $proceso['id_proceso'] ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                            </td>
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
</div>

<style>
    .border-left-primary { border-left: 4px solid #4e73df; }
    .border-left-warning { border-left: 4px solid #f6c23e; }
    .border-left-info { border-left: 4px solid #36b9cc; }
    .border-left-success { border-left: 4px solid #1cc88a; }
</style>

<?php require_once '../app/Views/layouts/footer.php'; ?>
