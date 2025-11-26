

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventario de Materiales</h1>
        <a href="/inventario/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Material
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Materiales</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/inventario" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, código..." value="<?= htmlspecialchars($filters['search']) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-control">
                            <option value="">Todas las Categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>" <?= $filters['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary btn-block">Filtrar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Marca/Modelo</th>
                            <th>Stock Total</th>
                            <th>Disponible</th>
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materiales as $material): ?>
                            <tr>
                                <td><?= htmlspecialchars($material['codigo']) ?></td>
                                <td><?= htmlspecialchars($material['nombre']) ?></td>
                                <td><?= htmlspecialchars($material['categoria']) ?></td>
                                <td><?= htmlspecialchars($material['marca'] . ' ' . $material['modelo']) ?></td>
                                <td><?= $material['stock_total'] ?></td>
                                <td class="<?= $material['stock_disponible'] == 0 ? 'text-danger font-weight-bold' : 'text-success' ?>">
                                    <?= $material['stock_disponible'] ?>
                                </td>
                                <td><?= htmlspecialchars($material['ubicacion']) ?></td>
                                <td>
                                    <a href="/inventario/edit/<?= $material['id_material'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/inventario/delete/<?= $material['id_material'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de dar de baja este material?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


