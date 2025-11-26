<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Editar Programa Académico</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/programa/update/<?= $programa['id_programa'] ?>" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Programa *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?= htmlspecialchars($programa['nombre']) ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione...</option>
                                <option value="BACHILLERATO" <?= $programa['tipo'] === 'BACHILLERATO' ? 'selected' : '' ?>>Bachillerato</option>
                                <option value="LICENCIATURA" <?= $programa['tipo'] === 'LICENCIATURA' ? 'selected' : '' ?>>Licenciatura</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modalidad" class="form-label">Modalidad *</label>
                            <select class="form-select" id="modalidad" name="modalidad" required>
                                <option value="">Seleccione...</option>
                                <option value="Lunes a Viernes" <?= $programa['modalidad'] === 'Lunes a Viernes' ? 'selected' : '' ?>>Lunes a Viernes</option>
                                <option value="Sabatina" <?= $programa['modalidad'] === 'Sabatina' ? 'selected' : '' ?>>Sabatina</option>
                                <option value="Virtual" <?= $programa['modalidad'] === 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="monto_colegiatura" class="form-label">Monto Colegiatura Mensual *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="monto_colegiatura" name="monto_colegiatura" 
                                       value="<?= $programa['monto_colegiatura'] ?>" required min="0">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="monto_inscripcion" class="form-label">Monto Inscripción *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" 
                                       id="monto_inscripcion" name="monto_inscripcion" 
                                       value="<?= $programa['monto_inscripcion'] ?>" required min="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="ACTIVO" <?= $programa['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                            <option value="INACTIVO" <?= $programa['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/programa/index" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save"></i> Actualizar Programa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
