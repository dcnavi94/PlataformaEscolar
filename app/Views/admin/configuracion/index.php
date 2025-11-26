<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Configuración Financiera Global</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Estos valores se utilizan como referencia predeterminada para nuevos registros.
                </div>

                <form action="<?= BASE_URL ?>/configuracion/update" method="POST">
                    <h6 class="border-bottom pb-2 mb-3">Montos Base</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Monto Inscripción (Default)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="monto_inscripcion" value="<?= $config['monto_inscripcion'] ?? 0 ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Monto Colegiatura (Default)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="monto_colegiatura" value="<?= $config['monto_colegiatura'] ?? 0 ?>">
                            </div>
                        </div>
                    </div>

                    <h6 class="border-bottom pb-2 mb-3 mt-4">Reglas de Cobro</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Día Límite de Pago</label>
                            <div class="input-group">
                                <span class="input-group-text">Día</span>
                                <input type="number" class="form-control" name="dia_limite_pago" value="<?= $config['dia_limite_pago'] ?? 10 ?>" min="1" max="31">
                            </div>
                            <div class="form-text">Ej: 10 de cada mes</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Recargo por Mora (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" name="porcentaje_recargo" value="<?= $config['porcentaje_recargo'] ?? 0 ?>">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Recargo Diario ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="monto_recargo_diario" value="<?= $config['monto_recargo_diario'] ?? 0 ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
