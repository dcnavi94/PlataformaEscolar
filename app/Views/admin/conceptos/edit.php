<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning"><h5>Editar Concepto</h5></div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/concepto/update/<?= $concepto['id_concepto'] ?>" method="POST">
                    <div class="mb-3">
                        <label>Nombre *</label>
                        <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($concepto['nombre']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="2"><?= htmlspecialchars($concepto['descripcion']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Monto Default</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="monto_default" value="<?= $concepto['monto_default'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Días de Tolerancia</label>
                            <input type="number" class="form-control" name="dias_tolerancia" value="<?= $concepto['dias_tolerancia'] ?>">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="aplica_beca" name="aplica_beca" value="1" <?= $concepto['aplica_beca'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="aplica_beca">Aplica para Beca (Descuento)</label>
                    </div>
                    <h6 class="mt-4">Configuración de Recargos</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Recargo Fijo</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="recargo_fijo" value="<?= $concepto['recargo_fijo'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Recargo Porcentaje</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" name="recargo_porcentaje" value="<?= $concepto['recargo_porcentaje'] ?>">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="<?= BASE_URL ?>/concepto/index" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
