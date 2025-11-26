<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Asignaciones</h2>
    <a href="<?= BASE_URL ?>/asignacion/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Asignación
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profesor</th>
                        <th>Materia</th>
                        <th>Grupo</th>
                        <th>Programa</th>
                        <th>Estado Calificación</th>
                        <th>Fecha Asignación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($asignaciones)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay asignaciones registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($asignaciones as $a): ?>
                            <tr>
                                <td><?= $a['id_asignacion'] ?></td>
                                <td><?= htmlspecialchars($a['profesor_nombre'] . ' ' . $a['profesor_apellidos']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($a['materia_codigo']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($a['materia_nombre']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($a['grupo_nombre']) ?></td>
                                <td><?= htmlspecialchars($a['programa_nombre']) ?></td>
                                <td>
                                    <?php
                                    $badgeClass = $a['estado_calificacion'] === 'ABIERTA' ? 'warning' : 'success';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $a['estado_calificacion'] ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($a['fecha_asignacion'] ?? $a['created_at'])) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/asignacion/edit/<?= $a['id_asignacion'] ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($a['estado_calificacion'] === 'ABIERTA'): ?>
                                        <a href="<?= BASE_URL ?>/asignacion/delete/<?= $a['id_asignacion'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar esta asignación?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted" title="No se puede eliminar asignación cerrada">
                                            <i class="bi bi-lock"></i>
                                        </span>
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
