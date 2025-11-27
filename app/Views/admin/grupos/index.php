<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Grupos</h1>
            <p class="text-muted mb-0">Administración de grupos académicos</p>
        </div>
        <a href="<?= BASE_URL ?>/grupo/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Grupo
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-collection me-2"></i>Listado de Grupos</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Programa</th>
                            <th>Periodo</th>
                            <th class="text-center"># Alumnos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grupos as $g): ?>
                        <tr>
                            <td class="fw-bold text-secondary">#<?= $g['id_grupo'] ?></td>
                            <td><strong><?= htmlspecialchars($g['nombre']) ?></strong></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($g['programa_nombre']) ?></span></td>
                            <td><?= htmlspecialchars($g['periodo_nombre']) ?></td>
                            <td class="text-center"><span class="badge bg-primary badge-modern"><?= $g['num_alumnos'] ?></span></td>
                            <td class="text-center"><span class="badge bg-<?= $g['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?> badge-modern"><?= $g['estado'] ?></span></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/grupo/alumnos/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Ver Alumnos"><i class="bi bi-people"></i></a>
                                <a href="<?= BASE_URL ?>/grupo/edit/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="<?= BASE_URL ?>/grupo/delete/<?= $g['id_grupo'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar?')" title="Eliminar"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
