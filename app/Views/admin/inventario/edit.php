

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Editar Material</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="/inventario/edit/<?= $material['id_material'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="codigo">Código (Barras/Interno)</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" value="<?= htmlspecialchars($material['codigo']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_categoria">Categoría</label>
                        <select class="form-control" id="id_categoria" name="id_categoria" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>" <?= $material['id_categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="nombre">Nombre del Material</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($material['nombre']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="marca">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($material['marca']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="modelo">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($material['modelo']) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($material['descripcion']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ubicacion">Ubicación Física</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?= htmlspecialchars($material['ubicacion']) ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Material</button>
                <a href="/inventario" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>


