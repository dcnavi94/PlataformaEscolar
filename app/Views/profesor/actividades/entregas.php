<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-file-earmark-text"></i> Entregas</h2>
            <p class="text-muted"><?= htmlspecialchars($actividad['titulo']) ?></p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5><?= htmlspecialchars($actividad['titulo']) ?></h5>
                            <p><?= nl2br(htmlspecialchars($actividad['descripcion'] ?? '')) ?></p>
                            <?php if ($actividad['archivo_adjunto']): ?>
                                <p>
                                    <a href="<?= BASE_URL ?>/uploads/<?= $actividad['archivo_adjunto'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Descargar archivo adjunto
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <dl class="row">
                                <dt class="col-sm-6">Materia:</dt>
                                <dd class="col-sm-6"><?= htmlspecialchars($actividad['materia_nombre']) ?></dd>
                                
                                <dt class="col-sm-6">Grupo:</dt>
                                <dd class="col-sm-6"><?= htmlspecialchars($actividad['grupo_nombre']) ?></dd>
                                
                                <dt class="col-sm-6">Tipo:</dt>
                                <dd class="col-sm-6"><span class="badge bg-secondary"><?= $actividad['tipo'] ?></span></dd>
                                
                                <dt class="col-sm-6">Puntos:</dt>
                                <dd class="col-sm-6"><?= $actividad['puntos_max'] ?></dd>
                                
                                <?php if ($actividad['fecha_limite']): ?>
                                    <dt class="col-sm-6">Límite:</dt>
                                    <dd class="col-sm-6"><?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-people"></i> Entregas de Estudiantes (<?= count($entregas) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($entregas)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay entregas aún.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th>Matrícula</th>
                                <th>Fecha Entrega</th>
                                <th>Archivo</th>
                                <th>Estado</th>
                                <th>Calificación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entregas as $entrega): ?>
                                <tr>
                                    <td><?= htmlspecialchars($entrega['nombre'] . ' ' . $entrega['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($entrega['matricula']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])) ?></td>
                                    <td>
                                        <?php if ($entrega['archivo_entrega']): ?>
                                            <a href="<?= BASE_URL ?>/uploads/<?= $entrega['archivo_entrega'] ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download"></i> Descargar
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Sin archivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($entrega['estado'] == 'CALIFICADA'): ?>
                                            <span class="badge bg-success">Calificada</span>
                                        <?php elseif ($entrega['estado'] == 'TARDIA'): ?>
                                            <span class="badge bg-warning">Tardía</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Enviada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($entrega['calificacion'] !== null): ?>
                                            <strong class="text-primary"><?= $entrega['calificacion'] ?> / <?= $actividad['puntos_max'] ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#calificarModal<?= $entrega['id_entrega'] ?>">
                                            <i class="bi bi-pencil"></i> Calificar
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Calificar -->
                                <div class="modal fade" id="calificarModal<?= $entrega['id_entrega'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= BASE_URL ?>/actividad/calificar/<?= $entrega['id_entrega'] ?>" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Calificar Entrega</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Estudiante:</strong> <?= htmlspecialchars($entrega['nombre'] . ' ' . $entrega['apellidos']) ?></p>
                                                    
                                                    <?php if ($entrega['comentarios']): ?>
                                                        <div class="mb-3">
                                                            <label class="form-label">Comentarios del alumno:</label>
                                                            <div class="alert alert-info">
                                                                <?= nl2br(htmlspecialchars($entrega['comentarios'])) ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="mb-3">
                                                        <label class="form-label">Calificación *</label>
                                                        <input type="number" name="calificacion" class="form-control" 
                                                               value="<?= $entrega['calificacion'] ?>" 
                                                               min="0" max="<?= $actividad['puntos_max'] ?>" 
                                                               step="0.01" required>
                                                        <small class="text-muted">Máximo: <?= $actividad['puntos_max'] ?> puntos</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Retroalimentación</label>
                                                        <textarea name="retroalimentacion" class="form-control" rows="3"><?= htmlspecialchars($entrega['retroalimentacion'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-save"></i> Guardar Calificación
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= BASE_URL ?>/actividad" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Actividades
        </a>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
