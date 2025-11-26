<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<h2 class="mb-4">
    <i class="bi bi-journal-text"></i> Mi Kardex
</h2>

<!-- Student Info Card -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person"></i> Información del Estudiante
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?></p>
                <p><strong>Matrícula:</strong> <?= htmlspecialchars($alumno['matricula'] ?? 'N/A') ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Programa:</strong> <?= htmlspecialchars($alumno['programa_nombre'] ?? 'N/A') ?></p>
                <p><strong>Promedio General:</strong> 
                    <span class="badge bg-<?= $promedio >= 70 ? 'success' : 'danger' ?> fs-6">
                        <?= number_format($promedio, 2) ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Grades Table -->
<?php if (empty($calificaciones)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Aún no tienes calificaciones registradas. 
        Tus calificaciones aparecerán aquí cuando tus profesores las registren.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-clipboard-check"></i> Historial de Calificaciones
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Materia</th>
                            <th>Grupo</th>
                            <th>Profesor</th>
                            <th>Período</th>
                            <th>Créditos</th>
                            <th class="text-center">Calificación</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $currentPeriodo = '';
                        foreach ($calificaciones as $c): 
                            // Add period separator
                            if ($currentPeriodo !== ($c['periodo_nombre'] ?? 'Sin período')) {
                                $currentPeriodo = $c['periodo_nombre'] ?? 'Sin período';
                                ?>
                                <tr class="table-secondary">
                                    <td colspan="8">
                                        <strong><i class="bi bi-calendar3"></i> <?= htmlspecialchars($currentPeriodo) ?></strong>
                                    </td>
                                </tr>
                            <?php } ?>
                            
                            <tr>
                                <td><strong><?= htmlspecialchars($c['materia_codigo']) ?></strong></td>
                                <td><?= htmlspecialchars($c['materia_nombre']) ?></td>
                                <td><?= htmlspecialchars($c['grupo_nombre']) ?></td>
                                <td>
                                    <small><?= htmlspecialchars($c['profesor_apellidos'] . ', ' . $c['profesor_nombre']) ?></small>
                                </td>
                                <td>
                                    <small class="text-muted"><?= htmlspecialchars($c['periodo_nombre'] ?? 'N/A') ?></small>
                                </td>
                                <td class="text-center"><?= $c['creditos'] ?></td>
                                <td class="text-center">
                                    <?php 
                                    $cal = floatval($c['calificacion']);
                                    $badgeClass = $cal >= 70 ? 'success' : 'danger';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?> fs-6">
                                        <?= number_format($cal, 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= htmlspecialchars($c['observaciones'] ?? '-') ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>Promedio General:</strong></td>
                            <td class="text-center">
                                <strong class="badge bg-<?= $promedio >= 70 ? 'success' : 'danger' ?> fs-5">
                                    <?= number_format($promedio, 2) ?>
                                </strong>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h6><i class="bi bi-info-circle"></i> Información</h6>
            <ul class="mb-0">
                <li>Calificación mínima aprobatoria: <strong>70</strong></li>
                <li>Calificaciones en escala de <strong>0 a 100</strong></li>
                <li>El promedio se calcula con base en todas las materias cursadas</li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../app/Views/layouts/footer.php'; ?>
