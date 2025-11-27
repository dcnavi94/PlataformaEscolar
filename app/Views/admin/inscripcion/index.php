<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Inscripciones en Línea</h1>
            <p class="text-muted mb-0">Gestión de solicitudes de inscripción</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/inscripcionadmin/periodos" class="btn btn-modern btn-modern-primary me-2">
                <i class="bi bi-calendar-plus me-2"></i>Gestionar Periodos
            </a>
            <a href="<?= BASE_URL ?>/inscripcionadmin/solicitudes" class="btn btn-modern btn-modern-secondary">
                <i class="bi bi-list-check me-2"></i>Ver Todas
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3">
                <div class="text-secondary fw-bold">Total</div>
                <h3 class="display-6 fw-bold text-dark mb-0"><?= $stats['total'] ?></h3>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3 border-bottom border-warning border-4">
                <div class="text-warning fw-bold">Pendientes</div>
                <h3 class="display-6 fw-bold text-warning mb-0"><?= $stats['pendientes'] ?></h3>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3 border-bottom border-info border-4">
                <div class="text-info fw-bold">En Revisión</div>
                <h3 class="display-6 fw-bold text-info mb-0"><?= $stats['en_revision'] ?></h3>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3 border-bottom border-success border-4">
                <div class="text-success fw-bold">Aprobadas</div>
                <h3 class="display-6 fw-bold text-success mb-0"><?= $stats['aprobadas'] ?></h3>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3 border-bottom border-primary border-4">
                <div class="text-primary fw-bold">Matriculados</div>
                <h3 class="display-6 fw-bold text-primary mb-0"><?= $stats['matriculados'] ?></h3>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card-modern h-100 text-center p-3 border-bottom border-danger border-4">
                <div class="text-danger fw-bold">Rechazadas</div>
                <h3 class="display-6 fw-bold text-danger mb-0"><?= $stats['rechazadas'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-clock-history me-2"></i>Solicitudes Pendientes de Revisión</h6>
        </div>
        <div class="card-body p-4">
            <?php if (empty($solicitudes_recientes)): ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox-fill fs-1 d-block mb-3"></i>
                    No hay solicitudes pendientes
                </div>
            <?php else: ?>
                <div class="table-responsive table-modern-container">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Nombre</th>
                                <th>Programa</th>
                                <th>CURP</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($solicitudes_recientes, 0, 10) as $sol): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $sol['folio'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($sol['nombre'] . ' ' . $sol['apellido_paterno']) ?></div>
                                    </td>
                                    <td><small><?= htmlspecialchars($sol['programa_nombre']) ?></small></td>
                                    <td><span class="badge bg-light text-dark border"><?= $sol['curp'] ?></span></td>
                                    <td class="text-center text-muted small"><?= date('d/m/Y', strtotime($sol['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/inscripcionadmin/revisar/<?= $sol['id_solicitud'] ?>" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> Revisar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
