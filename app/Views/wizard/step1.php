<?php $current_step = 1; require 'header.php'; ?>

<h3 class="mb-4"><i class="bi bi-building"></i> Datos de la Institución</h3>
<p class="text-muted">Comencemos configurando la información básica de tu institución educativa.</p>

<form method="POST" action="<?= BASE_URL ?>/wizard/step1">
    <div class="mb-3">
        <label for="nombre_institucion" class="form-label">Nombre de la Institución *</label>
        <input type="text" class="form-control" id="nombre_institucion" name="nombre_institucion" 
               value="<?= htmlspecialchars($saved_data['nombre_institucion'] ?? '') ?>" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" 
                   value="<?= htmlspecialchars($saved_data['telefono'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($saved_data['email'] ?? '') ?>">
        </div>
    </div>

    <div class="mb-4">
        <label for="direccion" class="form-label">Dirección</label>
        <textarea class="form-control" id="direccion" name="direccion" rows="2"><?= htmlspecialchars($saved_data['direccion'] ?? '') ?></textarea>
    </div>

    <div class="wizard-footer">
        <div></div>
        <button type="submit" class="btn btn-primary">
            Siguiente <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</form>

<?php require 'footer.php'; ?>
