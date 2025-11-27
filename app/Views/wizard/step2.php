<?php $current_step = 2; require 'header.php'; ?>

<h3 class="mb-4"><i class="bi bi-calendar3"></i> CreaciónPrimer Periodo Académico</h3>
<p class="text-muted">Define el primer periodo escolar (cuatrimestre/semestre).</p>

<form method="POST" action="<?= BASE_URL ?>/wizard/step2">
    <div class="mb-3">
        <label for="nombre_periodo" class="form-label">Nombre del Periodo *</label>
        <input type="text" class="form-control" id="nombre_periodo" name="nombre_periodo" 
               placeholder="Ej: Enero-Abril 2025" value="<?= htmlspecialchars($saved_data['nombre_periodo'] ?? '') ?>" required>
        <small class="text-muted">Ejemplo: Enero-Abril 2025, Cuatrimestre 1-2025</small>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                   value="<?= $saved_data['fecha_inicio'] ?? '' ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="fecha_fin" class="form-label">Fecha Fin *</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                   value="<?= $saved_data['fecha_fin'] ?? '' ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="anio" class="form-label">Año *</label>
            <input type="number" class="form-control" id="anio" name="anio" 
                   value="<?= $saved_data['anio'] ?? date('Y') ?>" min="2020" max="2030" required>
        </div>
    </div>

    <div class="mb-4">
        <label for="numero_periodo" class="form-label">Número de Periodo *</label>
        <select class="form-select" id="numero_periodo" name="numero_periodo" required>
            <option value="1" <?= ($saved_data['numero_periodo'] ?? 1) == 1 ? 'selected' : '' ?>>1 (Primer cuatrimestre/semestre)</option>
            <option value="2" <?= ($saved_data['numero_periodo'] ?? 1) == 2 ? 'selected' : '' ?>>2 (Segundo cuatrimestre/semestre)</option>
            <option value="3" <?= ($saved_data['numero_periodo'] ?? 1) == 3 ? 'selected' : '' ?>>3 (Tercer cuatrimestre/semestre)</option>
        </select>
    </div>

    <div class="wizard-footer">
        <a href="<?= BASE_URL ?>/wizard/step1" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Anterior
        </a>
        <button type="submit" class="btn btn-primary">
            Siguiente <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</form>

<?php require 'footer.php'; ?>
