<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-folder-check"></i> Procesos de Titulación</h2>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group">
                <a href="<?= BASE_URL ?>/titulacionadmin/procesos" 
                   class="btn btn-outline-secondary <?= !$filtro_estado ? 'active' : '' ?>">Todos</a>
                <a href="<?= BASE_URL ?>/titulacionadmin/procesos?estado=SOLICITADO" 
                   class="btn btn-outline-secondary <?= $filtro_estado == 'SOLICITADO' ? 'active' : '' ?>">Solicitados</a>
                <a href="<?= BASE_URL ?>/titulacionadmin/procesos?estado=EN_REVISION" 
                   class="btn btn-outline-warning <?= $filtro_estado == 'EN_REVISION' ? 'active' : '' ?>">En Revisión</a>
                <a href="<?= BASE_URL ?>/titulacionadmin/procesos?estado=APROBADO" 
                   class="btn btn-outline-info <?= $filtro_estado == 'APROBADO' ? 'active' : '' ?>">Aprobados</a>
                <a href="<?= BASE_URL ?>/titulacionadmin/procesos?estado=TITULADO" 
                   class="btn btn-outline-success <?= $filtro_estado == 'TITULADO' ? 'active' : '' ?>">Titulados</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Alumno</th>
                                    <th>Matrícula</th>
                                    <th>Programa</th>
                                    <th>Modalidad</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($procesos)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No hay procesos</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($procesos as $proceso): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($proceso['numero_folio']) ?></strong></td>
                                            <td><?= htmlspecialchars($proceso['alumno_nombre'] . ' ' . $proceso['alumno_apellidos']) ?></td>
                                            <td><?= htmlspecialchars($proceso['matricula'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($proceso['programa_nombre']) ?></td>
                                            <td><span class="badge bg-secondary"><?= $proceso['modalidad'] ?></span></td>
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
                                                    <i class="bi bi-eye"></i> Revisar
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
