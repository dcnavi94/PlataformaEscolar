<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Contenido</h1>
        <a href="/curso/index/<?= $asignacion['id_asignacion'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Curso
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detalles del Contenido</h6>
                </div>
                <div class="card-body">
                    <form action="/curso/updateContent" method="POST">
                        <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
                        <input type="hidden" name="content_type" value="<?= $content_type ?>">
                        <input type="hidden" name="id_content" value="<?= $content['id_actividad'] ?? $content['id_recurso'] ?>">
                        
                        <!-- Content Type Display (Read Only) -->
                        <div class="mb-4 text-center">
                            <span class="badge bg-primary fs-6">
                                <?= $content_type === 'ACTIVITY' ? 'Actividad' : 'Material/Recurso' ?>
                            </span>
                        </div>

                        <!-- Common Fields -->
                        <div class="mb-3">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($content['titulo']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($content['descripcion']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tema / Unidad <small class="text-muted">(Selecciona un tema dentro del módulo)</small></label>
                            <select name="id_tema" class="form-select">
                                <option value="">-- Sin tema (General) --</option>
                                <?php foreach ($modulos as $modulo): ?>
                                    <optgroup label="<?= htmlspecialchars($modulo['titulo']) ?>">
                                        <?php if (empty($modulo['temas'])): ?>
                                            <option disabled>⚠ No hay temas en este módulo (Crea uno primero)</option>
                                        <?php else: ?>
                                            <?php foreach ($modulo['temas'] as $tema): ?>
                                                <option value="<?= $tema['id_tema'] ?>" <?= ($content['id_tema'] == $tema['id_tema']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tema['titulo']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">El contenido debe asignarse a un Tema específico dentro de un Módulo.</div>
                        </div>

                        <!-- Activity Specific Fields -->
                        <?php if ($content_type === 'ACTIVITY'): ?>
                        <div id="activity_fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Actividad</label>
                                    <select name="tipo_actividad" class="form-select">
                                        <option value="TAREA" <?= $content['tipo'] == 'TAREA' ? 'selected' : '' ?>>Tarea</option>
                                        <option value="EXAMEN" <?= $content['tipo'] == 'EXAMEN' ? 'selected' : '' ?>>Examen</option>
                                        <option value="QUIZ" <?= $content['tipo'] == 'QUIZ' ? 'selected' : '' ?>>Quiz Rápido</option>
                                        <option value="VIDEO_CUESTIONARIO" <?= $content['tipo'] == 'VIDEO_CUESTIONARIO' ? 'selected' : '' ?>>Video Cuestionario</option>
                                        <option value="PROYECTO" <?= $content['tipo'] == 'PROYECTO' ? 'selected' : '' ?>>Proyecto</option>
                                        <option value="LECTURA" <?= $content['tipo'] == 'LECTURA' ? 'selected' : '' ?>>Control de Lectura</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Puntos Máximos</label>
                                    <input type="number" name="puntos_max" class="form-control" value="<?= $content['puntos_max'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha Límite</label>
                                    <input type="datetime-local" name="fecha_limite" class="form-control" value="<?= $content['fecha_limite'] ? date('Y-m-d\TH:i', strtotime($content['fecha_limite'])) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Link de Video (Opcional)</label>
                                    <input type="url" name="link_video" class="form-control" value="<?= htmlspecialchars($content['link_video'] ?? '') ?>" placeholder="https://youtube.com/...">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Resource Specific Fields -->
                        <?php if ($content_type === 'RESOURCE'): ?>
                        <div id="resource_fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Recurso</label>
                                    <select name="tipo_recurso" class="form-select">
                                        <option value="DOCUMENTO" <?= $content['tipo'] == 'DOCUMENTO' ? 'selected' : '' ?>>Documento</option>
                                        <option value="VIDEO" <?= $content['tipo'] == 'VIDEO' ? 'selected' : '' ?>>Video</option>
                                        <option value="LINK" <?= $content['tipo'] == 'LINK' ? 'selected' : '' ?>>Enlace Web</option>
                                        <option value="PRESENTACION" <?= $content['tipo'] == 'PRESENTACION' ? 'selected' : '' ?>>Presentación</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL / Enlace</label>
                                    <input type="url" name="url" class="form-control" value="<?= htmlspecialchars($content['url'] ?? '') ?>" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
