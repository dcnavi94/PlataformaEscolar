<?php require_once '../app/Views/layouts/header_profesor.php'; ?>

<h2 class="mb-4">
    <i class="bi bi-mortarboard"></i> Panel de Profesor
</h2>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-person-badge"></i> Bienvenido, 
                    <?= htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellidos']) ?>
                </h5>
                <p class="card-text text-muted">
                    <?php if (!empty($profesor['especialidad'])): ?>
                        <i class="bi bi-award"></i> Especialidad: <?= htmlspecialchars($profesor['especialidad']) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3">
    <i class="bi bi-clipboard-check"></i> Mis Asignaciones
</h4>

<?php if (empty($asignaciones)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No tienes asignaciones actualmente. 
        Contacta con el administrador para que te asignen grupos y materias.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($asignaciones as $a): ?>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-book"></i> <?= htmlspecialchars($a['materia_nombre']) ?>
                        </h5>
                        <small class="text-muted">Código: <?= htmlspecialchars($a['materia_codigo']) ?></small>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="bi bi-people"></i> <strong>Grupo:</strong> 
                            <?= htmlspecialchars($a['grupo_nombre']) ?>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-diagram-3"></i> <strong>Programa:</strong> 
                            <?= htmlspecialchars($a['programa_nombre']) ?>
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-person-check"></i> <strong>Estudiantes:</strong> 
                            <?= $a['total_alumnos'] ?>
                        </p>
                        <p class="mb-3">
                            <strong>Estado:</strong> 
                            <?php if ($a['estado_calificacion'] === 'ABIERTA'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-unlock"></i> Abierta
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-lock"></i> Cerrada
                                </span>
                            <?php endif; ?>
                        </p>

                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/curso/index/<?= $a['id_asignacion'] ?>" 
                               class="btn btn-info text-white btn-sm">
                                <i class="bi bi-layout-text-window-reverse"></i> Gestionar Curso (LMS)
                            </a>

                            <?php if ($a['estado_calificacion'] === 'ABIERTA'): ?>
                                <?php if ($a['total_alumnos'] > 0): ?>
                                    <a href="<?= BASE_URL ?>/profesor/calificar/<?= $a['id_asignacion'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> Calificar Estudiantes
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-info-circle"></i> Sin estudiantes
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/profesor/calificar/<?= $a['id_asignacion'] ?>" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Calificaciones
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <small>
                            <i class="bi bi-calendar-event"></i> 
                            Asignado: <?= date('d/m/Y', strtotime($a['fecha_asignacion'] ?? $a['created_at'])) ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once '../app/Views/layouts/footer.php'; ?>
