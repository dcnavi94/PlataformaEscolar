<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Profesores</h1>
            <p class="text-muted mb-0">Directorio de personal docente</p>
        </div>
        <a href="<?= BASE_URL ?>/profesor/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Profesor
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-person-video3 me-2"></i>Listado de Profesores</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Especialidad</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($profesores)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-person-video3 fs-1 d-block mb-3"></i>
                                    No hay profesores registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($profesores as $p): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $p['id_profesor'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['email']) ?></td>
                                    <td><?= htmlspecialchars($p['telefono'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['especialidad'] ?? 'N/A') ?></span></td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = $p['estado'] === 'ACTIVO' ? 'success' : 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> badge-modern"><?= $p['estado'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/profesor/edit/<?= $p['id_profesor'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/profesor/delete/<?= $p['id_profesor'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle" 
                                           onclick="return confirm('¿Está seguro de eliminar este profesor?')"
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
