

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registrar Nuevo Préstamo</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="/adminprestamos/create" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_usuario">ID Usuario (Alumno/Profesor)</label>
                        <input type="number" class="form-control" id="id_usuario" name="id_usuario" required placeholder="Ingrese ID del usuario">
                        <small class="form-text text-muted">Puede buscar el ID en el módulo de Usuarios.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_material">Material</label>
                        <select class="form-control" id="id_material" name="id_material" required>
                            <option value="">Seleccione material...</option>
                            <?php foreach ($materiales as $material): ?>
                                <?php if ($material['stock_disponible'] > 0): ?>
                                    <option value="<?= $material['id_material'] ?>">
                                        <?= htmlspecialchars($material['nombre']) ?> (<?= $material['codigo'] ?>) - Disp: <?= $material['stock_disponible'] ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_devolucion">Fecha Devolución Esperada</label>
                        <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required min="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
                <a href="/adminprestamos" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>


