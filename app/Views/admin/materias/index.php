<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Materias</h1>
            <p class="text-muted mb-0">Catálogo de asignaturas académicas</p>
        </div>
        <a href="<?= BASE_URL ?>/materia/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nueva Materia
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-book me-2"></i>Listado de Materias</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Programa</th>
                            <th class="text-center">Créditos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($materias)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-book fs-1 d-block mb-3"></i>
                                    No hay materias registradas
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($materias as $m): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $m['id_materia'] ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($m['codigo']) ?></span></td>
                                    <td><strong><?= htmlspecialchars($m['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($m['programa_nombre'] ?? 'N/A') ?></td>
                                    <td class="text-center"><?= $m['creditos'] ?></td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = $m['estado'] === 'ACTIVO' ? 'success' : 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> badge-modern"><?= $m['estado'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/materia/edit/<?= $m['id_materia'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/materia/delete/<?= $m['id_materia'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle" 
                                           onclick="return confirm('¿Está seguro de eliminar esta materia?')"
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
