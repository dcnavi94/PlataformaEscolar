<div class="container-fluid animate-fade-in">
    <div class="page-header-modern">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Gestión de Alumnos</h1>
            <p class="text-muted mb-0">Administración de estudiantes y matrículas</p>
        </div>
        <a href="<?= BASE_URL ?>/alumnoadmin/create" class="btn btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i> Inscribir Alumno
        </a>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="m-0 text-white"><i class="bi bi-people me-2"></i>Listado de Alumnos</h6>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive table-modern-container">
                <table class="table table-modern" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Programa</th>
                            <th>Grupo</th>
                            <th class="text-center">Beca</th>
                            <th class="text-center">Estatus</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($alumnos)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    No hay alumnos registrados
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($alumnos as $a): ?>
                                <tr>
                                    <td class="fw-bold text-secondary">#<?= $a['id_alumno'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellidos']) ?></div>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($a['correo']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['programa_nombre'] ?? 'N/A') ?></span></td>
                                    <td><?= htmlspecialchars($a['grupo_nombre'] ?? 'N/A') ?></td>
                                    <td class="text-center">
                                        <?php if ($a['porcentaje_beca'] > 0): ?>
                                            <span class="badge bg-success badge-modern"><?= $a['porcentaje_beca'] ?>%</span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $badgeClass = match($a['estatus']) {
                                            'INSCRITO' => 'success',
                                            'BAJA' => 'danger',
                                            'EGRESADO' => 'info',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?> badge-modern"><?= $a['estatus'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/alumnoadmin/show/<?= $a['id_alumno'] ?>" 
                                           class="btn btn-sm btn-outline-info rounded-circle me-1" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/alumnoadmin/edit/<?= $a['id_alumno'] ?>" 
                                           class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/alumnoadmin/delete/<?= $a['id_alumno'] ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-circle" 
                                           onclick="return confirm('¿Está seguro de eliminar este alumno?')"
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
