<?php $current_step = 4; require 'header.php'; ?>

<h3 class="mb-4"><i class="bi bi-gear"></i> Configuración de Pagos</h3>
<p class="text-muted">Define las políticas de pago de tu institución.</p>

<form method="POST" action="<?= BASE_URL ?>/wizard/step4">
    <div class="mb-3">
        <label for="dia_limite_pago" class="form-label">Día Límite de Pago Mensual *</label>
        <input type="number" class="form-control" id="dia_limite_pago" name="dia_limite_pago" 
               value="<?= $saved_data['dia_limite_pago'] ?? 10 ?>" min="1" max="31" required>
        <small class="text-muted">Día del mes en que vencen las colegiaturas (ej: 10 = día 10 de cada mes)</small>
    </div>

    <div class="mb-3">
        <label for="tipo_penalizacion" class="form-label">Tipo de Penalización por Mora *</label>
        <select class="form-select" id="tipo_penalizacion" name="tipo_penalizacion" required>
            <option value="MONTO" <?= ($saved_data['tipo_penalizacion'] ?? 'MONTO') == 'MONTO' ? 'selected' : '' ?>>Monto Fijo</option>
            <option value="PORCENTAJE" <?= ($saved_data['tipo_penalizacion'] ?? '') == 'PORCENTAJE' ? 'selected' : '' ?>>Porcentaje</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="valor_penalizacion" class="form-label">Valor de la Penalización *</label>
        <div class="input-group">
            <span class="input-group-text" id="penalizacion-symbol">$</span>
            <input type="number" class="form-control" id="valor_penalizacion" name="valor_penalizacion" 
                   value="<?= $saved_data['valor_penalizacion'] ?? 0 ?>" min="0" step="0.01" required>
        </div>
        <small class="text-muted">Si seleccionaste "Porcentaje", ingresa el porcentaje (ej: 5 para 5%)</small>
    </div>

    <div class="wizard-footer">
        <a href="<?= BASE_URL ?>/wizard/step3" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Anterior
        </a>
        <button type="submit" class="btn btn-primary">
            Siguiente <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</form>

<script>
document.getElementById('tipo_penalizacion').addEventListener('change', function() {
    const symbol = this.value === 'PORCENTAJE' ? '%' : '$';
    document.getElementById('penalizacion-symbol').textContent = symbol;
});
</script>

<?php require 'footer.php'; ?>
