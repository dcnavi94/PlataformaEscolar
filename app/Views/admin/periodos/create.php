<div class="card">
    <div class="card-header bg-primary text-white"><h5>Nuevo Periodo</h5></div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/periodo/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" class="form-control" name="nombre" required placeholder="Enero - Abril 2025">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Año</label>
                    <input type="number" class="form-control" name="anio" value="<?= date('Y') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Número Periodo</label>
                    <select class="form-select" name="numero_periodo" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Fecha Inicio</label>
                    <input type="date" class="form-control" name="fecha_inicio" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Fecha Fin</label>
                    <input type="date" class="form-control" name="fecha_fin" required>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/periodo/index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
