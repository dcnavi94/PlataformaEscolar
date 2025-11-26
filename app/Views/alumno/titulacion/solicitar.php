<?php require_once '../app/Views/layouts/header_alumno.php'; ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-file-earmark-check"></i> Solicitar Titulación</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/titulacionalumno/solicitar">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" 
                                   value="<?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']) ?>" 
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Modalidad de Titulación *</label>
                            <select name="modalidad" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="TESIS">Tesis</option>
                                <option value="EXAMEN_PROFESIONAL">Examen Profesional</option>
                                <option value="PROYECTO">Proyecto Terminal</option>
                                <option value="PROMEDIO">Por Promedio</option>
                                <option value="CURSO">Curso de Titulación</option>
                            </select>
                            <small class="form-text text-muted">
                                Selecciona la modalidad bajo la cual deseas titularte
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones (Opcional)</label>
                            <textarea name="observaciones" class="form-control" rows="3" 
                                      placeholder="Ingresa cualquier comentario adicional sobre tu solicitud"></textarea>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Importante:</h6>
                            <ul class="mb-0">
                                <li>Una vez enviada tu solicitud, se te asignará un folio de seguimiento</li>
                                <li>Deberás cargar los documentos probatorios requeridos</li>
                                <li>El personal administrativo revisará tu documentación</li>
                                <li>Recibirás notificaciones sobre el estado de tu proceso</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i> Enviar Solicitud
                            </button>
                            <a href="<?= BASE_URL ?>/titulacionalumno" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/Views/layouts/footer.php'; ?>
