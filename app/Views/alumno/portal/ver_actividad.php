<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="<?= BASE_URL ?>/alumnoportal/actividades" class="btn btn-sm btn-secondary mb-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <h2><?= htmlspecialchars($actividad['titulo']) ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Activity Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Detalles de la Actividad</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Materia:</strong> <?= htmlspecialchars($actividad['materia_nombre']) ?></p>
                            <p><strong>Grupo:</strong> <?= htmlspecialchars($actividad['grupo_nombre']) ?></p>
                            <p><strong>Profesor:</strong> <?= htmlspecialchars($actividad['profesor_nombre']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tipo:</strong> <span class="badge bg-secondary"><?= $actividad['tipo'] ?></span></p>
                            <p><strong>Puntos:</strong> <?= $actividad ['puntos_max'] ?></p>
                            <?php if ($actividad['fecha_limite']): ?>
                                <p><strong>Fecha Límite:</strong> 
                                    <span class="badge <?= strtotime($actividad['fecha_limite']) < time() ? 'bg-danger' : 'bg-warning' ?>">
                                        <?= date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <h5>Descripción</h5>
                    <p><?= nl2br(htmlspecialchars($actividad['descripcion'] ?? 'Sin descripción')) ?></p>

                    <?php if ($actividad['archivo_adjunto']): ?>
                        <hr>
                        <h5>Archivo Adjunto</h5>
                        <a href="<?= BASE_URL ?>/uploads/<?= $actividad['archivo_adjunto'] ?>" 
                           target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Descargar archivo
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submission Status or Form -->
            <?php if ($entrega): ?>
                <!-- Already Submitted -->
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-check-circle"></i> Tarea Entregada</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha de Entrega:</strong> <?= date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])) ?></p>
                        
                        <?php if ($entrega['archivo_entrega']): ?>
                            <p><strong>Archivo Entregado:</strong></p>
                            <a href="<?= BASE_URL ?>/uploads/<?= $entrega['archivo_entrega'] ?>" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Ver mi archivo
                            </a>
                        <?php endif; ?>

                        <?php if ($entrega['comentarios']): ?>
                            <hr>
                            <p><strong>Mis Comentarios:</strong></p>
                            <p><?= nl2br(htmlspecialchars($entrega['comentarios'])) ?></p>
                        <?php endif; ?>

                        <?php if ($entrega['estado'] == 'CALIFICADA'): ?>
                            <hr>
                            <div class="alert alert-success">
                                <h5><i class="bi bi-star-fill"></i> Calificación: <?= $entrega['calificacion'] ?> / <?= $actividad['puntos_max'] ?></h5>
                                <?php if ($entrega['retroalimentacion']): ?>
                                    <p><strong>Retroalimentación del profesor:</strong></p>
                                    <p><?= nl2br(htmlspecialchars($entrega['retroalimentacion'])) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-clock"></i> Tu entrega está pendiente de calificación.
                            </div>
                        <?php endif; ?>

                        <?php if ($entrega['estado'] == 'TARDIA'): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Esta entrega fue marcada como tardía.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($actividad['permite_entrega']): ?>
                <!-- Submission Form -->
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-upload"></i> Entregar Tarea</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($actividad['fecha_limite'] && strtotime($actividad['fecha_limite']) < time()): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i> 
                                <strong>Atención:</strong> La fecha límite ha pasado. Tu entrega será marcada como tardía.
                            </div>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>/alumnoportal/entregar" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_actividad" value="<?= $actividad['id_actividad'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Archivo *</label>
                                <input type="file" name="archivo" class="form-control" required
                                       accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.png,.zip,.txt">
                                <small class="text-muted">
                                    Tipos permitidos: PDF, DOC, PPT, imágenes, ZIP, TXT (máx 10MB)
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comentarios (opcional)</label>
                                <textarea name="comentarios" class="form-control" rows="3" 
                                          placeholder="Agrega cualquier comentario sobre tu entrega..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Entregar Tarea
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Esta actividad no requiere entrega de archivos.
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Estado</h5>
                </div>
                <div class="card-body">
                    <?php if ($entrega): ?>
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-2 text-success">Tarea Entregada</h5>
                            <?php if ($entrega['estado'] == 'CALIFICADA'): ?>
                                <p class="text-muted mb-0">Calificada</p>
                            <?php else: ?>
                                <p class="text-muted mb-0">En revisión</p>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($actividad['fecha_limite'] && strtotime($actividad['fecha_limite']) < time()): ?>
                        <div class="text-center">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-2 text-danger">Vencida</h5>
                            <p class="text-muted mb-0">Puedes entregar como tardía</p>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="bi bi-clock-fill text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-2 text-warning">Pendiente</h5>
                            <p class="text-muted mb-0">No has entregado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer_alumno.php'; ?>
