

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Inventario de Materiales</h1>
            <p class="text-muted mb-0">Gestión de activos y recursos escolares</p>
        </div>
        <a href="/inventario/create" class="btn btn-modern btn-modern-primary">
            <i class="fas fa-plus"></i> Nuevo Material
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-list-ul me-2"></i>Listado de Materiales</h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="/inventario" class="mb-4 form-modern">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Nombre, código..." value="<?= htmlspecialchars($filters['search']) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select">
                            <option value="">Todas las Categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>" <?= $filters['categoria'] == $cat['id_categoria'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-modern btn-modern-secondary w-100">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Marca/Modelo</th>
                            <th>Stock Total</th>
                            <th>Disponible</th>
                            <th>Ubicación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materiales as $material): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($material['codigo']) ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($material['nombre']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($material['descripcion'], 0, 30)) ?>...</small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($material['categoria']) ?></span></td>
                                <td><?= htmlspecialchars($material['marca'] . ' ' . $material['modelo']) ?></td>
                                <td class="text-center"><?= $material['stock_total'] ?></td>
                                <td class="text-center">
                                    <?php if($material['stock_disponible'] == 0): ?>
                                        <span class="badge bg-danger badge-modern">Agotado</span>
                                    <?php elseif($material['stock_disponible'] < 5): ?>
                                        <span class="badge bg-warning text-dark badge-modern"><?= $material['stock_disponible'] ?> (Bajo)</span>
                                    <?php else: ?>
                                        <span class="badge bg-success badge-modern"><?= $material['stock_disponible'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><i class="bi bi-geo-alt text-muted me-1"></i><?= htmlspecialchars($material['ubicacion']) ?></td>
                                <td class="text-center">
                                    <a href="/inventario/edit/<?= $material['id_material'] ?>" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/inventario/delete/<?= $material['id_material'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Estás seguro de dar de baja este material?');" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(empty($materiales)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                    <p class="text-muted">No se encontraron materiales con los filtros seleccionados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


