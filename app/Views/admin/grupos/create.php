<div class="card">
    <div class="card-header bg-primary text-white"><h5>Nuevo Grupo</h5></div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/grupo/store" method="POST">
            <div class="mb-3">
                <label>Nombre del Grupo</label>
                <input type="text" class="form-control" name="nombre" required placeholder="ISW-2025-1">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Programa</label>
                    <select class="form-select" name="id_programa" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($programas as $p): ?>
                            <option value="<?= $p['id_programa'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Periodo</label>
                    <select class="form-select" name="id_periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $per): ?>
                            <option value="<?= $per['id_periodo'] ?>"><?= htmlspecialchars($per['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/grupo/index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
