<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-file-earmark-check"></i> Mis Entregas</h2>
            <p class="text-muted">Historial de todas tus tareas entregadas</p>
        </div>
    </div>

    <?php if (empty($entregas)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No has realizado entregas aún.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Materia</th>
                                <th>Fecha Entrega</th>
                                <th>Estado</th>
                                <th>Calificación</th>
                                <th>Retroalimentación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entregas as $entrega): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($entrega['actividad_titulo']) ?></strong></td>
                                    <td><small class="text-muted"><?= htmlspecialchars($entrega['materia_nombre']) ?></small></td>
                                    <td><?= date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])) ?></td>
                                    <td>
                                        <?php if ($entrega['estado'] == 'CALIFICADA'): ?>
                                            <span class="badge bg-success">Calificada</span>
                                        <?php elseif ($entrega['estado'] == 'TARDIA'): ?>
                                            <span class="badge bg-warning">Tardía</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">En Revisión</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($entrega['calificacion'] !== null): ?>
                                            <strong class="text-primary">
                                                <?= number_format($entrega['calificacion'], 2) ?> / <?= $entrega['puntos_max'] ?>
                                            </strong>
                                            <?php 
                                            $porcentaje = ($entrega['calificacion'] / $entrega['puntos_max']) * 100;
                                            if ($porcentaje >= 70): ?>
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            <?php else: ?>
                                                <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($entrega['retroalimentacion']): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#feedbackModal<?= $entrega['id_entrega'] ?>">
                                                <i class="bi bi-chat-left-text"></i> Ver
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="feedbackModal<?= $entrega['id_entrega'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Retroalimentación del Profesor</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Actividad:</strong> <?= htmlspecialchars($entrega['actividad_titulo']) ?></p>
                                                            <p><strong>Calificación:</strong> <?= $entrega['calificacion'] ?> / <?= $entrega['puntos_max'] ?></p>
                                                            <hr>
                                                            <p><?= nl2br(htmlspecialchars($entrega['retroalimentacion'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Summary -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3><?= count($entregas) ?></h3>
                    <p class="text-muted mb-0">Total Entregas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <?php 
                    $calificadas = 0;
                    foreach ($entregas as $e) {
                        if ($e['estado'] == 'CALIFICADA') $calificadas++;
                    }
                    ?>
                    <h3><?= $calificadas ?></h3>
                    <p class="text-muted mb-0">Calificadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <?php 
                    $promedio = 0;
                    $count = 0;
                    foreach ($entregas as $e) {
                        if ($e['calificacion'] !== null) {
                            $promedio += ($e['calificacion'] / $e['puntos_max']) * 100;
                            $count++;
                        }
                    }
                    $promedio = $count > 0 ? $promedio / $count : 0;
                    ?>
                    <h3 class="<?= $promedio >= 70 ? 'text-success' : 'text-warning' ?>"><?= number_format($promedio, 1) ?>%</h3>
                    <p class="text-muted mb-0">Promedio General</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
