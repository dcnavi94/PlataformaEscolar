<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Programas Académicos</h1>
            <p class="text-muted mb-0">Oferta educativa y planes de estudio</p>
        </div>
        <a href="<?= BASE_URL ?>/programa/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Programa
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-journal-bookmark me-2"></i>Listado de Programas</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Modalidad</th>
                            <th class="text-center">Colegiatura</th>
                            <th class="text-center">Inscripción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($programas)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-journal-bookmark fs-1 d-block mb-3"></i>
                                    No hay programas registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($programas as $programa): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $programa['id_programa'] ?></td>
                                    <td><strong><?= htmlspecialchars($programa['nombre']) ?></strong></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($programa['tipo']) ?></span></td>
                                    <td><?= htmlspecialchars($programa['modalidad']) ?></td>
                                    <td class="text-center">$<?= number_format($programa['monto_colegiatura'], 2) ?></td>
                                    <td class="text-center">$<?= number_format($programa['monto_inscripcion'], 2) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $programa['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?> badge-modern">
                                            <?= $programa['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/programa/edit/<?= $programa['id_programa'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/programa/delete/<?= $programa['id_programa'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle" 
                                           onclick="return confirm('¿Está seguro de eliminar este programa?')"
                                           title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
