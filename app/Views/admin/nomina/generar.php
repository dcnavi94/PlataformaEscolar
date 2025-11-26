<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Generar Nómina</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>/nomina/generar" method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="id_profesor" class="form-label">Profesor</label>
                        <select class="form-select" id="id_profesor" name="id_profesor" required>
                            <option value="">Seleccione un profesor</option>
                            <?php foreach ($profesores as $profesor): ?>
                                <option value="<?= $profesor['id_profesor'] ?>">
                                    <?= htmlspecialchars($profesor['apellidos'] . ' ' . $profesor['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" id="inicio" name="inicio" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fin" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" id="fin" name="fin" required>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Se calculará el pago basado en las horas aprobadas dentro del rango de fechas seleccionado y la tarifa por hora del profesor.
                </div>

                <button type="submit" class="btn btn-primary">Generar Cálculo</button>
                <a href="<?= BASE_URL ?>/nomina" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
