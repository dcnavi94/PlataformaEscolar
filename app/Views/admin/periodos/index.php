<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Periodos Académicos</h1>
            <p class="text-muted mb-0">Gestión de ciclos escolares</p>
        </div>
        <a href="<?= BASE_URL ?>/periodo/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Periodo
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-calendar-range me-2"></i>Listado de Periodos</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th class="text-center">Periodo</th>
                            <th class="text-center">Fechas</th>
                            <th class="text-center">Año</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($periodos as $p): ?>
                        <tr>
                            <td class="fw-bold text-secondary">#<?= $p['id_periodo'] ?></td>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td class="text-center"><span class="badge bg-info badge-modern"><?= $p['numero_periodo'] ?></span></td>
                            <td class="text-center text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($p['fecha_fin'])) ?>
                            </td>
                            <td class="text-center fw-bold"><?= $p['anio'] ?></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/periodo/edit/<?= $p['id_periodo'] ?>" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="<?= BASE_URL ?>/periodo/delete/<?= $p['id_periodo'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar?')" title="Eliminar"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
