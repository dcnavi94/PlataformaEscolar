<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-plus-circle"></i> Nueva Actividad</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= BASE_URL ?>/actividad/store" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Título *</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Materia/Grupo *</label>
                                <select name="id_asignacion" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($asignaciones as $asig): ?>
                                        <option value="<?= $asig['id_asignacion'] ?>">
                                            <?= htmlspecialchars($asig['materia_nombre']) ?> - <?= htmlspecialchars($asig['grupo_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="tipo" class="form-select">
                                    <option value="TAREA">Tarea</option>
                                    <option value="EXAMEN">Examen</option>
                                    <option value="PROYECTO">Proyecto</option>
                                    <option value="LECTURA">Lectura</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Publicación</label>
                                <input type="datetime-local" name="fecha_publicacion" class="form-control" 
                                       value="<?= date('Y-m-d\TH:i') ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha Límite</label>
                                <input type="datetime-local" name="fecha_limite" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Archivo Adjunto (opcional)</label>
                            <input type="file" name="archivo" class="form-control" 
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.png,.zip">
                            <small class="text-muted">Tipos permitidos: PDF, DOC, PPT, imágenes, ZIP (máx 10MB)</small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Configuración</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Puntos Máximos</label>
                                    <input type="number" name="puntos_max" class="form-control" 
                                           value="100" min="0" step="0.01">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select name="estado" class="form-select">
                                        <option value="BORRADOR">Borrador</option>
                                        <option value="ACTIVA" selected>Activa</option>
                                        <option value="CERRADA">Cerrada</option>
                                    </select>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="permite_entrega" 
                                           id="permite_entrega" checked>
                                    <label class="form-check-label" for="permite_entrega">
                                        Permite entrega de alumnos
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Crear Actividad
                    </button>
                    <a href="<?= BASE_URL ?>/actividad" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
