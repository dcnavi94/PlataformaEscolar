<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-file-earmark-check"></i> Inscripciones en Línea</h2>
            <p class="text-muted">Gestión de solicitudes de inscripción</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= $stats['total'] ?></h3>
                    <p class="text-muted mb-0">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="text-warning"><?= $stats['pendientes'] ?></h3>
                    <p class="text-muted mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h3 class="text-info"><?= $stats['en_revision'] ?></h3>
                    <p class="text-muted mb-0">En Revisión</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="text-success"><?= $stats['aprobadas'] ?></h3>
                    <p class="text-muted mb-0">Aprobadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h3 class="text-primary"><?= $stats['matriculados'] ?></h3>
                    <p class="text-muted mb-0">Matriculados</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3 class="text-danger"><?= $stats['rechazadas'] ?></h3>
                    <p class="text-muted mb-0">Rechazadas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Acciones Rápidas</h5>
                    <a href="<?= BASE_URL ?>/inscripcionadmin/periodos" class="btn btn-primary me-2">
                        <i class="bi bi-calendar-plus"></i> Gestionar Periodos
                    </a>
                    <a href="<?= BASE_URL ?>/inscripcionadmin/solicitudes" class="btn btn-secondary">
                        <i class="bi bi-list-check"></i> Ver Todas las Solicitudes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Solicitudes Pendientes de Revisión</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($solicitudes_recientes)): ?>
                        <p class="text-muted">No hay solicitudes pendientes</p>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Nombre</th>
                                    <th>Programa</th>
                                    <th>CURP</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($solicitudes_recientes, 0, 10) as $sol): ?>
                                    <tr>
                                        <td><strong><?= $sol['folio'] ?></strong></td>
                                        <td><?= htmlspecialchars($sol['nombre'] . ' ' . $sol['apellido_paterno']) ?></td>
                                        <td><?= htmlspecialchars($sol['programa_nombre']) ?></td>
                                        <td><?= $sol['curp'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($sol['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/inscripcionadmin/revisar/<?= $sol['id_solicitud'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Revisar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
