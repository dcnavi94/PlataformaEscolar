<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Materias</h2>
    <a href="<?= BASE_URL ?>/materia/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Materia
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Programa</th>
                        <th>Créditos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($materias)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay materias registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($materias as $m): ?>
                            <tr>
                                <td><?= $m['id_materia'] ?></td>
                                <td><strong><?= htmlspecialchars($m['codigo']) ?></strong></td>
                                <td><?= htmlspecialchars($m['nombre']) ?></td>
                                <td><?= htmlspecialchars($m['programa_nombre'] ?? 'N/A') ?></td>
                                <td><?= $m['creditos'] ?></td>
                                <td>
                                    <?php
                                    $badgeClass = $m['estado'] === 'ACTIVO' ? 'success' : 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $m['estado'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/materia/edit/<?= $m['id_materia'] ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/materia/delete/<?= $m['id_materia'] ?>" 
                                       class="btn btn-sm btn-danger" 
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
