<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Crear Contenido</h1>
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
                    <form action="/curso/storeContent" method="POST">
                        <input type="hidden" name="id_asignacion" value="<?= $asignacion['id_asignacion'] ?>">
                        
                        <!-- Content Type Selection -->
                        <div class="mb-4 text-center">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="content_type" id="type_activity" value="ACTIVITY" checked onchange="toggleFields()">
                                <label class="btn btn-outline-primary" for="type_activity">
                                    <i class="fas fa-clipboard-list me-2"></i>Actividad
                                </label>

                                <input type="radio" class="btn-check" name="content_type" id="type_resource" value="RESOURCE" onchange="toggleFields()">
                                <label class="btn btn-outline-primary" for="type_resource">
                                    <i class="fas fa-book me-2"></i>Material/Recurso
                                </label>
                            </div>
                        </div>

                        <!-- Common Fields -->
                        <div class="mb-3">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"></textarea>
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
                                                <option value="<?= $tema['id_tema'] ?>">
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
                        <div id="activity_fields">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Actividad</label>
                                    <select name="tipo_actividad" class="form-select">
                                        <option value="TAREA">Tarea</option>
                                        <option value="EXAMEN">Examen</option>
                                        <option value="QUIZ">Quiz Rápido</option>
                                        <option value="VIDEO_CUESTIONARIO">Video Cuestionario</option>
                                        <option value="PROYECTO">Proyecto</option>
                                        <option value="LECTURA">Control de Lectura</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Puntos Máximos</label>
                                    <input type="number" name="puntos_max" class="form-control" value="100">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha Límite</label>
                                    <input type="datetime-local" name="fecha_limite" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Link de Video (Opcional)</label>
                                    <input type="url" name="link_video" class="form-control" placeholder="https://youtube.com/...">
                                    <small class="text-muted">Para Video Cuestionarios</small>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Specific Fields -->
                        <div id="resource_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Recurso</label>
                                    <select name="tipo_recurso" class="form-select">
                                        <option value="DOCUMENTO">Documento</option>
                                        <option value="VIDEO">Video</option>
                                        <option value="LINK">Enlace Web</option>
                                        <option value="PRESENTACION">Presentación</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL / Enlace</label>
                                    <input type="url" name="url" class="form-control" placeholder="https://...">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Publicar Contenido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFields() {
    const isActivity = document.getElementById('type_activity').checked;
    const activityFields = document.getElementById('activity_fields');
    const resourceFields = document.getElementById('resource_fields');

    if (isActivity) {
        activityFields.style.display = 'block';
        resourceFields.style.display = 'none';
    } else {
        activityFields.style.display = 'none';
        resourceFields.style.display = 'block';
    }
}
</script>

<?php require_once '../app/Views/layouts/footer.php'; ?>
