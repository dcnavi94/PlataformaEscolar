<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Asignaciones</h1>
            <p class="text-muted mb-0">Control de carga académica docente</p>
        </div>
        <a href="<?= BASE_URL ?>/asignacion/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nueva Asignación
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-journal-check me-2"></i>Listado de Asignaciones</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profesor</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Programa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($asignaciones)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-journal-check fs-1 d-block mb-3"></i>
                                    No hay asignaciones registradas
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($asignaciones as $a): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $a['id_asignacion'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($a['profesor_nombre'] . ' ' . $a['profesor_apellidos']) ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($a['materia_codigo']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($a['materia_nombre']) ?></div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['grupo_nombre']) ?></span></td>
                                    <td><small><?= htmlspecialchars($a['programa_nombre']) ?></small></td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = $a['estado_calificacion'] === 'ABIERTA' ? 'warning' : 'success';
                                        $iconClass = $a['estado_calificacion'] === 'ABIERTA' ? 'unlock' : 'lock';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> badge-modern">
                                            <i class="bi bi-<?= $iconClass ?> me-1"></i><?= $a['estado_calificacion'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-muted small"><?= date('d/m/Y', strtotime($a['fecha_asignacion'] ?? $a['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/asignacion/edit/<?= $a['id_asignacion'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($a['estado_calificacion'] === 'ABIERTA'): ?>
                                            <a href="<?= BASE_URL ?>/asignacion/delete/<?= $a['id_asignacion'] ?>" 
                                               class="btn btn-sm btn-outline-danger rounded-circle" 
                                               onclick="return confirm('¿Está seguro de eliminar esta asignación?')"
                                               title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle" disabled title="No se puede eliminar asignación cerrada">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        <?php endif; ?>
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
