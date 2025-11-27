<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Configuración Financiera</h1>
            <p class="text-muted mb-0">Parámetros globales del sistema</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="m-0 text-white"><i class="bi bi-gear me-2"></i>Configuración Global</h6>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle fs-4 me-3"></i>
                        <div>Estos valores se utilizan como referencia predeterminada para nuevos registros.</div>
                    </div>

                    <form action="<?= BASE_URL ?>/configuracion/update" method="POST" class="form-modern">
                        <h6 class="text-primary border-bottom pb-2 mb-4">Montos Base</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Monto Inscripción (Default)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                    <input type="number" step="0.01" class="form-control border-start-0 ps-0" name="monto_inscripcion" value="<?= $config['monto_inscripcion'] ?? 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Monto Colegiatura (Default)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                    <input type="number" step="0.01" class="form-control border-start-0 ps-0" name="monto_colegiatura" value="<?= $config['monto_colegiatura'] ?? 0 ?>">
                                </div>
                            </div>
                        </div>

                        <h6 class="text-primary border-bottom pb-2 mb-4">Reglas de Cobro</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Día Límite de Pago</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Día</span>
                                    <input type="number" class="form-control" name="dia_limite_pago" value="<?= $config['dia_limite_pago'] ?? 10 ?>" min="1" max="31">
                                </div>
                                <div class="form-text text-muted small mt-1">Ej: 10 de cada mes</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Recargo por Mora (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control border-end-0" name="porcentaje_recargo" value="<?= $config['porcentaje_recargo'] ?? 0 ?>">
                                    <span class="input-group-text bg-light border-start-0">%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Recargo Diario ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">$</span>
                                    <input type="number" step="0.01" class="form-control border-start-0 ps-0" name="monto_recargo_diario" value="<?= $config['monto_recargo_diario'] ?? 0 ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit" class="btn btn-modern btn-modern-primary px-4">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
