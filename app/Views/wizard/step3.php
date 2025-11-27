<?php $current_step = 3; require 'header.php'; ?>

<h3 class="mb-4"><i class="bi bi-mortarboard"></i> Crear Primer Programa Académico</h3>
<p class="text-muted">Configura el primer programa educativo que ofrecerá tu institución.</p>

<form method="POST" action="<?= BASE_URL ?>/wizard/step3">
    <div class="mb-3">
        <label for="nombre_programa" class="form-label">Nombre del Programa *</label>
        <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" 
               placeholder="Ej: Licenciatura en Administración" value="<?= htmlspecialchars($saved_data['nombre_programa'] ?? '') ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tipo" class="form-label">Tipo de Programa *</label>
            <select class="form-select" id="tipo" name="tipo" required>
                <option value="LICENCIATURA" <?= ($saved_data['tipo'] ?? '') == 'LICENCIATURA' ? 'selected' : '' ?>>Licenciatura</option>
                <option value="BACHILLERATO" <?= ($saved_data['tipo'] ?? '') == 'BACHILLERATO' ? 'selected' : '' ?>>Bachillerato</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="modalidad" class="form-label">Modalidad *</label>
            <select class="form-select" id="modalidad" name="modalidad" required>
                <option value="Virtual" <?= ($saved_data['modalidad'] ?? 'Virtual') == 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                <option value="Lunes a Viernes" <?= ($saved_data['modalidad'] ?? '') == 'Lunes a Viernes' ? 'selected' : '' ?>>Lunes a Viernes</option>
                <option value="Sabatina" <?= ($saved_data['modalidad'] ?? '') == 'Sabatina' ? 'selected' : '' ?>>Sabatina</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="monto_colegiatura" class="form-label">Colegiatura Mensual *</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="monto_colegiatura" name="monto_colegiatura" 
                       value="<?= $saved_data['monto_colegiatura'] ?? '' ?>" min="0" step="0.01" required>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <label for="monto_inscripcion" class="form-label">Inscripción *</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="monto_inscripcion" name="monto_inscripcion" 
                       value="<?= $saved_data['monto_inscripcion'] ?? '' ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>

    <div class="wizard-footer">
        <a href="<?= BASE_URL ?>/wizard/step2" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Anterior
        </a>
        <button type="submit" class="btn btn-primary">
            Siguiente <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</form>

<?php require 'footer.php'; ?>
