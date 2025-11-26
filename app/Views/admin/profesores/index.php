<?php require_once '../app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Profesores</h2>
    <a href="<?= BASE_URL ?>/profesor/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Profesor
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($profesores)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay profesores registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($profesores as $p): ?>
                            <tr>
                                <td><?= $p['id_profesor'] ?></td>
                                <td><strong><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></strong></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
                                <td><?= htmlspecialchars($p['telefono'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($p['especialidad'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = $p['estado'] === 'ACTIVO' ? 'success' : 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $p['estado'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/profesor/edit/<?= $p['id_profesor'] ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/profesor/delete/<?= $p['id_profesor'] ?>" 
                                       class="btn btn-sm btn-danger" 
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

<?php require_once '../app/Views/layouts/footer.php'; ?>
