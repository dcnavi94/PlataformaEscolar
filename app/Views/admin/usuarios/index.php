<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Usuarios</h1>
            <p class="text-muted mb-0">Administración de cuentas y permisos</p>
        </div>
        <a href="<?= BASE_URL ?>/usuarioadmin/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-people-fill me-2"></i>Listado de Usuarios Administrativos</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th class="text-center">Módulos</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Fecha Creación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    No hay usuarios administrativos registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></div>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($usuario['email']) ?></td>
                                    <td><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary badge-modern"><?= $usuario['modulos_con_acceso'] ?> módulos</span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($usuario['activo']): ?>
                                            <span class="badge bg-success badge-modern">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary badge-modern">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center text-muted small"><?= date('d/m/Y', strtotime($usuario['created_at'])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/usuarioadmin/edit/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/usuarioadmin/toggleStatus/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" 
                                           title="<?= $usuario['activo'] ? 'Desactivar' : 'Activar' ?>"
                                           onclick="return confirm('¿Cambiar estado del usuario?')">
                                            <i class="bi bi-<?= $usuario['activo'] ? 'pause' : 'play' ?>-circle"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/usuarioadmin/delete/<?= $usuario['id_usuario_admin'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle" title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este usuario?')">
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
