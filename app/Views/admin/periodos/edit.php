<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editar Periodo</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/periodo/update/<?= $periodo['id_periodo'] ?>" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Periodo *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?= htmlspecialchars($periodo['nombre']) ?>" required>
                        <small class="text-muted">Ej: Enero-Abril, Mayo-Agosto</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio *</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="<?= $periodo['fecha_inicio'] ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="<?= $periodo['fecha_fin'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="anio" class="form-label">Año *</label>
                            <input type="number" class="form-control" id="anio" name="anio" 
                                   value="<?= $periodo['anio'] ?>" required min="2020" max="2050">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_periodo" class="form-label">Número de Periodo *</label>
                            <select class="form-select" id="numero_periodo" name="numero_periodo" required>
                                <option value="1" <?= $periodo['numero_periodo'] == 1 ? 'selected' : '' ?>>1 (Primer Cuatrimestre)</option>
                                <option value="2" <?= $periodo['numero_periodo'] == 2 ? 'selected' : '' ?>>2 (Segundo Cuatrimestre)</option>
                                <option value="3" <?= $periodo['numero_periodo'] == 3 ? 'selected' : '' ?>>3 (Tercer Cuatrimestre)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/periodo/index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Periodo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
