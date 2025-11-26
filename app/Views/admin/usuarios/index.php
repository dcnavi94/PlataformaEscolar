<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="bi bi-people-fill"></i> Gestión de Usuarios Administrativos</h2>
        <a href="<?= BASE_URL ?>/usuarioadmin/create" class="btn institutional-orange-btn">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Módulos</th>
                        <th>Estado</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay usuarios administrativos registrados</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= $usuario['modulos_con_acceso'] ?> módulos</span>
                                </td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($usuario['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= BASE_URL ?>/usuarioadmin/edit/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/usuarioadmin/toggleStatus/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="<?= $usuario['activo'] ? 'Desactivar' : 'Activar' ?>"
                                           onclick="return confirm('¿Cambiar estado del usuario?')">
                                            <i class="bi bi-<?= $usuario['activo'] ? 'pause' : 'play' ?>-circle"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/usuarioadmin/delete/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-danger" title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
